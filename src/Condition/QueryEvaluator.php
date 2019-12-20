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
     * Config for this class.
     *
     * @var array
     */
    protected $_config = [];

    /**
     * Setup.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->_config = $config;
    }

    /**
     * Parse query.
     *
     * @param string $query
     *
     * @return array
     */
    public function parse(string $query)
    {
        // map parenthesis
        $parenthesis = $this->_mapParenthesis($query);

        // remove redundant
        if (isset($parenthesis[0]) && $parenthesis[0] == \strlen($query) - 1) {
            $query = \substr($query, 1, -1);
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
     * Parse condition (column operator value).
     *
     * @param string $condition
     *
     * @return array
     */
    public function parseCondition(string $condition)
    {
        $condition = \array_map('trim', \explode(' ', $condition, 3));

        if (\count($condition) < 3) {
            throw new QueryEvaluatorException(['placeholder' => $condition[0]], QueryEvaluatorException::INVALID_CONDITION);
        }

        list($column, $operator, $value) = $condition;

        if (!isset($this->_config[$column])) {
            throw new QueryEvaluatorException(['placeholder' => $column], QueryEvaluatorException::UNKNOWN_PLACEHOLDER);
        }

        if (!\in_array($operator, $this->_config[$column]['operators'])) {
            throw new QueryEvaluatorException(['placeholder' => $column, 'operator' => $operator], QueryEvaluatorException::UNKNOWN_OPERATOR);
        }

        return $this->_parseCondition($this->_config[$column]['field'], $operator, $value);
    }

    /**
     * Parse condition divided to field, operator and value.
     *
     * @param string $field
     * @param string $operator
     * @param string $value
     *
     * @return array
     */
    protected function _parseCondition(string $field, string $operator, string $value)
    {
        $addNull = false;

        switch ($operator) {
            case 'CONTAINS':
                $operator = ' LIKE';
                $value = '%' . $value . '%';

                break;

            case 'IN':
                $operator = ' IN';
                $value = \array_map('trim', \explode(',', \trim($value, '()')));

                break;

            case 'NOTIN':
                $addNull = true;
                $operator = ' NOT IN';
                $value = \array_map('trim', \explode(',', \trim($value, '()')));

                break;

            case '=':
                $operator = '';

                break;

            case 'NOT':
                $addNull = true;
                $operator = ' !=';

                break;

            default:
                $operator = ' ' . $operator;

                break;
        }

        if (isset($this->_config['_prefix']) && !\strpos($field, '.')) {
            $field = $this->_config['_prefix'] . '.' . $field;
        }

        if ($addNull) {
            return ['OR' => [
                $field . $operator => $value,
                $field . ' IS NULL'
            ]];
        }

        return [$field . $operator => $value];
    }

    /**
     * Map parenthesis location.
     *
     * @param string $query
     *
     * @return array<int,int>
     */
    protected function _mapParenthesis(string $query): array
    {
        $parenthesis = [];
        $lastParenthesis = [];

        for ($i = 0; $i < \strlen($query); ++$i) {
            // opening
            if ($query[$i] == static::PARENTHESIS_OPEN) {
                $parenthesis[$i] = -1;
                $lastParenthesis[] = $i;
            }

            if ($query[$i] == static::PARENTHESIS_CLOSE) {
                if (!\count($lastParenthesis)) {
                    throw new QueryEvaluatorException(['position' => $i], QueryEvaluatorException::MISSING_OPENING_PARENTHESIS);
                }

                /** @var int $last */
                $last = \array_pop($lastParenthesis);
                $parenthesis[$last] = $i;
            }
        }

        if (\count($lastParenthesis)) {
            throw new QueryEvaluatorException(['position' => \array_shift($lastParenthesis)], QueryEvaluatorException::MISSING_CLOSING_PARENTHESIS);
        }

        return $parenthesis;
    }

    /**
     * Split query by operator.
     *
     * @param string         $query
     * @param string         $operator
     * @param array<int,int> $parenthesis
     *
     * @return string[]
     */
    protected function _split(string $query, string $operator, array $parenthesis): array
    {
        \preg_match_all('/\b' . \preg_quote($operator) . '\b/', $query, $matches, PREG_OFFSET_CAPTURE);

        if (empty($matches[0])) {
            return [];
        }

        $result = [];
        $start = 0;

        foreach ($matches[0] as $match) {
            $isRoot = true;
            $match = (int) $match[1];

            foreach ($parenthesis as $from => $to) {
                if ($match > $from && $match < $to) {
                    $isRoot = false;
                }
            }

            if ($isRoot) {
                $result[] = \trim(\substr($query, $start, $match - $start));
                $start = $match + \strlen($operator);
            }
        }

        $result[] = \trim(\substr($query, $start));

        if (\count($result) == 1) {
            return [];
        }

        return $result;
    }
}
