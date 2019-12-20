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
 * Class enabling to provide configuration for two independent "prefixes".
 *
 * Constructor accepts configuration array in format:
 *     'prefix' => [
 *         'placeholder' => [
 *             'field' => 'field name',
 *             'operators' => [available operators]
 *         ]
 *     ]
 *
 * @author John Diegor <john.diegor@gmail.com>
 */
class QueryEvaluatorTwofold extends QueryEvaluator
{
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

        list($prefixedColumn, $operator, $value) = $condition;

        // check if field has a prefix
        if (\strpos($prefixedColumn, '.') === false) {
            throw new QueryEvaluatorException(['placeholder' => $prefixedColumn], QueryEvaluatorException::UNKNOWN_PREFIX);
        }

        list($prefix, $column) = \explode('.', $prefixedColumn, 2);

        if (!isset($this->_config[$prefix])) {
            throw new QueryEvaluatorException(['placeholder' => $prefix], QueryEvaluatorException::UNKNOWN_PREFIX);
        }

        if (!isset($this->_config[$prefix][$column])) {
            throw new QueryEvaluatorException(['placeholder' => $prefixedColumn], QueryEvaluatorException::UNKNOWN_PLACEHOLDER);
        }

        if (!\in_array($operator, $this->_config[$prefix][$column]['operators'])) {
            throw new QueryEvaluatorException(['placeholder' => $prefixedColumn, 'operator' => $operator], QueryEvaluatorException::UNKNOWN_OPERATOR);
        }

        return $this->_parseCondition($prefix . '.' . $this->_config[$prefix][$column]['field'], $operator, $value);
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
        $parsedCondition = parent::_parseCondition($field, $operator, $value);

        // parse twofold
        return $this->_parseTwofold($parsedCondition);
    }

    /**
     * Evaluate if right side of the condition is expression.
     *
     * @param array $parsedCondition
     *
     * @return array
     */
    protected function _parseTwofold(array $parsedCondition)
    {
        $key = \key($parsedCondition);
        $value = \current($parsedCondition);

        // handle IS NULL
        if ($key === 'OR') {
            $result = [];

            foreach ($value as $k => $v) {
                $result = \array_merge($result, $this->_parseTwofold([$k => $v]));
            }

            return ['OR' => $result];
        }

        if (!\is_string($key) || \is_array($value) || \strpos($value, '.') === false) {
            return $parsedCondition;
        }

        // handle operator =
        if (!\strpos($key, ' ')) {
            $key .= ' =';
        }

        list($prefix, $field) = \explode('.', $value, 2);

        // validate existing prefix and field
        if (!isset($this->_config[$prefix]) || !isset($this->_config[$prefix][$field])) {
            return $parsedCondition;
        }

        return [$key . ' ' . $prefix . '.' . $this->_config[$prefix][$field]['field']];
    }
}
