<?php
namespace Riesenia\Utility\Condition;

/**
 * This class will enable to provide configuration for two independent “prefixes”.
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
     * Setup
     *
     * @param array configuration options
     */
    public function __construct(array $config)
    {
        if (!is_array($config) or count($config) == 0) {
            throw new QueryEvaluatorException('Invalid config format', QueryEvaluatorException::MISSING_CONFIG_FORMAT);
        }

        $this->_config = $config;
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

        // check if field has a prefix
        if (strpos($column, '.') !== false) {
            list($prefix, $column) = explode('.', $column);

            if (!isset($this->_config[$prefix][$column])) {
                throw new QueryEvaluatorException(['placeholder' => "{$prefix}.{$column}"], QueryEvaluatorException::UNKNOWN_PLACEHOLDER);
            }

            if (!in_array($operator, $this->_config[$prefix][$column]['operators'])) {
                throw new QueryEvaluatorException(['placeholder' => "{$prefix}.{$column}", 'operator' => $operator], QueryEvaluatorException::UNKNOWN_OPERATOR);
            }

            $parsedCondition = $this->_parseCondition($prefix . "." . $this->_config[$prefix][$column]['field'], $operator, $value);
        } else {
            throw new QueryEvaluatorException(['placeholder' => $column], QueryEvaluatorException::MISSING_PREFIX);
        }

        return $parsedCondition;
    }
}
