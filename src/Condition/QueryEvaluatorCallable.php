<?php
namespace Riesenia\Utility\Condition;

/**
 * Class parsing written condition (query) to a function returning bool.
 *
 * @author Tomas Saghy <segy@riesenia.com>
 */
class QueryEvaluatorCallable extends QueryEvaluator
{
    /**
     * Parse query
     *
     * @param string query
     * @return array
     */
    public function parse($query)
    {
        $parsed = parent::parse($query);

        if (is_callable($parsed)) {
            return $parsed;
        }

        $operator = key($parsed);
        $conditions = current($parsed);

        return function ($item) use ($operator, $conditions) {
            foreach ($conditions as $condition) {
                // one true for OR
                if ($operator == 'OR' && $condition($item)) {
                    return true;
                }
                // one false for AND
                if ($operator == 'AND' && !$condition($item)) {
                    return false;
                }
            }

            return $operator == 'OR' ? false : true;
        };
    }

    /**
     * Parse condition divided to field, operator and value
     *
     * @param string field
     * @return array
     */
    protected function _parseCondition($field, $operator, $value)
    {
        switch ($operator) {
            case 'CONTAINS':
                return function ($item) use ($field, $value) {
                    return isset($item[$field]) && stripos(iconv('UTF-8', 'ASCII//TRANSLIT', $item[$field]), iconv('UTF-8', 'ASCII//TRANSLIT', $value)) !== false;
                };

            case 'IN':
                $value = array_map('trim', explode(',', trim($value, '()')));
                return function ($item) use ($field, $value) {
                    return isset($item[$field]) && in_array($item[$field], $value);
                };

            case 'NOT IN':
                $value = array_map('trim', explode(',', trim($value, '()')));
                return function ($item) use ($field, $value) {
                    return !isset($item[$field]) || !in_array($item[$field], $value);
                };

            case '=':
                return function ($item) use ($field, $value) {
                    return isset($item[$field]) && $item[$field] == $value;
                };

            case 'NOT':
                return function ($item) use ($field, $value) {
                    return !isset($item[$field]) || $item[$field] != $value;
                };

            case '>=':
                return function ($item) use ($field, $value) {
                    return isset($item[$field]) && $item[$field] >= $value;
                };

            case '<=':
                return function ($item) use ($field, $value) {
                    return isset($item[$field]) && $item[$field] <= $value;
                };

            case '>':
                return function ($item) use ($field, $value) {
                    return isset($item[$field]) && $item[$field] > $value;
                };

            case '<':
                return function ($item) use ($field, $value) {
                    return isset($item[$field]) && $item[$field] < $value;
                };
        }
    }
}
