<?php
/**
 * This file is part of riesenia/utility package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */

declare(strict_types=1);

namespace Riesenia\Utility\Condition;

/**
 * Class parsing written condition (query) to a function returning bool.
 *
 * @author Tomas Saghy <segy@riesenia.com>
 */
class QueryEvaluatorCallable extends QueryEvaluator
{
    /**
     * Parse query.
     *
     * @param string $query
     *
     * @return callable
     */
    public function parse(string $query)
    {
        $parsed = parent::parse($query);

        if (\is_callable($parsed)) {
            return $parsed;
        }

        $operator = \key($parsed);
        $conditions = \current($parsed);

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
     * Parse condition divided to field, operator and value.
     *
     * @param string $field
     * @param string $operator
     * @param string $value
     *
     * @return callable
     */
    protected function _parseCondition(string $field, string $operator, string $value)
    {
        switch ($operator) {
            case 'CONTAINS':
                return function ($item) use ($field, $value) {
                    return isset($item[$field]) && \stripos((string) \iconv('UTF-8', 'ASCII//TRANSLIT', $item[$field]), (string) \iconv('UTF-8', 'ASCII//TRANSLIT', $value)) !== false;
                };

            case 'IN':
                $value = \array_map('trim', \explode(',', \trim($value, '()')));

                return function ($item) use ($field, $value) {
                    return isset($item[$field]) && \in_array($item[$field], $value);
                };

            case 'NOTIN':
                $value = \array_map('trim', \explode(',', \trim($value, '()')));

                return function ($item) use ($field, $value) {
                    return !isset($item[$field]) || !\in_array($item[$field], $value);
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

        throw new QueryEvaluatorException(['placeholder' => $field, 'operator' => $operator], QueryEvaluatorException::UNKNOWN_OPERATOR);
    }
}
