<?php
/**
 * This file is part of riesenia/utility package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */

declare(strict_types=1);

namespace Riesenia\Utility\Kendo\Table\Column;

/**
 * Price column.
 *
 * @author Tomas Saghy <segy@riesenia.com>
 */
class Number extends Base
{
    /**
     * Type used for model type property.
     *
     * @var string
     */
    protected $_type = 'number';

    /**
     * Predefined class.
     *
     * @var string
     */
    protected $_class = 'tableColumn tableNumber';

    /**
     * Construct the column.
     *
     * @param array  $options
     * @param string $tableId
     */
    public function __construct(array $options, string $tableId)
    {
        if (isset($options['arrow']) && $options['arrow']) {
            if (!isset($options['compare_field'])) {
                $options['compare_field'] = '0';
            }

            $this->_format = '# if (%field% < ' . $options['compare_field'] . ') { #<span class="glyphicon glyphicon-arrow-down" aria-hidden="true"></span># } else if (%field% > ' . $options['compare_field'] . ') { #<span class="glyphicon glyphicon-arrow-up" aria-hidden="true"></span># } # ' . $this->_format;
        }

        if (isset($options['colorize']) && $options['colorize']) {
            if (!isset($options['compare_field'])) {
                $options['compare_field'] = '0';
            }

            $this->_format = '<span class="#: %field% < ' . $options['compare_field'] . ' ? "tableColumnNegative" : (%field% > ' . $options['compare_field'] . ' ? "tableColumnPositive" : "tableColumnNeutral") #">' . $this->_format . '</span>';
        }

        parent::__construct($options, $tableId);
    }
}
