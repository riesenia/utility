<?php
namespace Riesenia\Utility\Condition;

/**
 * Class parsing written condition (query) to array.
 *
 * Constructor accepts configuration array in format:
 *     'placeholder' => [
 *         'field' => 'field name',
 *         'operators' => [available operators]
 *     ]
 *
 * @author Tomas Saghy <segy@riesenia.com>
 */
class QueryEvaluator
{
    // operators
    const OPERATOR_AND = 'AND';
    const OPERATOR_OR = 'OR';

    // parenthesis
    const PARENTHESIS_OPEN = '(';
    const PARENTHESIS_CLOSE = ')';

    /**
     * Config for this class
     *
     * @var array
     */
    protected $_config = [];

    /**
     * Setup
     *
     * @param array configuration options
     */
    public function __construct(array $config)
    {
        $this->_config = $config;
    }

    /**
     * Parse query
     *
     * @param string query
     * @return array
     */
    public function parse($query)
    {
        // map parenthesis
        $parenthesis = $this->_mapParenthesis($query);

        // remove redundant
        if (isset($parenthesis[0]) && $parenthesis[0] == strlen($query) - 1) {
            $query = substr($query, 1, -1);
            unset($parenthesis[0]);
        }

        // start with ORs
        $subqueries = $this->_split($query, static::OPERATOR_OR, $parenthesis);

        // continue with ANDs
        if (!$subqueries) {
            $subqueries = $this->_split($query, static::OPERATOR_AND, $parenthesis);

            // simple condition
            if (!$subqueries) {
                return $this->parseCondition($query);
            }

            $parsed = ['AND' => []];

            foreach ($subqueries as $subquery) {
                $parsed['AND'][] = (new static($this->_config))->parse($subquery);
            }

            return $parsed;
        }

        $parsed = ['OR' => []];

        foreach ($subqueries as $subquery) {
            $parsed['OR'][] = (new static($this->_config))->parse($subquery);
        }

        return $parsed;
    }

    /**
     * Parse condition (column operator value)
     *
     * @param string condition
     * @return array
     */
    public function parseCondition($condition)
    {
        $condition = array_map('trim', explode(' ', $condition, 3));

        if (count($condition) < 3) {
            throw new QueryEvaluatorException(['placeholder' => $condition[0]], QueryEvaluatorException::INVALID_CONDITION);
        }

        list($column, $operator, $value) = $condition;

        if (!isset($this->_config[$column])) {
            throw new QueryEvaluatorException(['placeholder' => $column], QueryEvaluatorException::UNKNOWN_PLACEHOLDER);
        }

        if (!in_array($operator, $this->_config[$column]['operators'])) {
            throw new QueryEvaluatorException(['placeholder' => $column, 'operator' => $operator], QueryEvaluatorException::UNKNOWN_OPERATOR);
        }

        return $this->_parseCondition($this->_config[$column]['field'], $operator, $value);
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
                $operator = ' LIKE';
                $value = '%' . $value . '%';
                break;

            case 'IN':
            case 'NOT IN':
                $operator = ' ' . $operator;
                $value = array_map('trim', explode(',', trim($value, '()')));
                break;

            case '=':
                $operator = '';
                break;

            default:
                $operator = ' ' . $operator;
                break;
        }

        if ($this->_config['_prefix'] && !strpos($field, '.')) {
            $field = $this->_config['_prefix'] . '.' . $field;
        }

        return [$field . $operator => $value];
    }

    /**
     * Map parenthesis location
     *
     * @param string query
     * @return array
     */
    protected function _mapParenthesis($query)
    {
        $parenthesis = [];
        $lastParenthesis = [];

        for ($i = 0; $i < strlen($query); $i++) {
            // opening
            if ($query{$i} == static::PARENTHESIS_OPEN) {
                $parenthesis[$i] = -1;
                $lastParenthesis[] = $i;
            }

            if ($query{$i} == static::PARENTHESIS_CLOSE) {
                if (!count($lastParenthesis)) {
                    throw new QueryEvaluatorException(['position' => $i], QueryEvaluatorException::MISSING_OPENING_PARENTHESIS);
                }

                $last = array_pop($lastParenthesis);
                $parenthesis[$last] = $i;
            }
        }

        if (count($lastParenthesis)) {
            throw new QueryEvaluatorException(['position' => array_shift($lastParenthesis)], QueryEvaluatorException::MISSING_CLOSING_PARENTHESIS);
        }

        return $parenthesis;
    }

    /**
     * Split query by operator
     *
     * @param string query
     * @param string operator
     * @param array parenthesis
     * @return array|bool
     */
    protected function _split($query, $operator, array $parenthesis)
    {
        preg_match_all('/\b' . preg_quote($operator) . '\b/', $query, $matches, PREG_OFFSET_CAPTURE);

        if (empty($matches[0])) {
            return false;
        }

        $result = [];
        $start = 0;

        foreach ($matches[0] as $match) {
            $isRoot = true;

            foreach ($parenthesis as $from => $to) {
                if ($match[1] > $from && $match[1] < $to) {
                    $isRoot = false;
                }
            }

            if ($isRoot) {
                $result[] = trim(substr($query, $start, $match[1] - $start));
                $start = $match[1] + strlen($operator);
            }
        }

        $result[] = trim(substr($query, $start));

        if (count($result) == 1) {
            return false;
        }

        return $result;
    }
}
