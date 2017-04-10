<?php
namespace Riesenia\Utility\Kendo\Table\Column;

/**
 * Price column
 *
 * @author Tomas Saghy <segy@riesenia.com>
 */
class Number extends Base
{
    /**
     * Type used for model type property
     *
     * @var string
     */
    protected $_type = 'number';

    /**
     * Predefined class
     *
     * @var string
     */
    protected $_class = 'tableColumn tableNumber';

    /**
     * Construct the column
     *
     * @param array options
     * @param string table id
     */
    public function __construct(array $options, $tableId)
    {
        if (isset($options['arrow']) && $options['arrow']) {
            $this->_format = '# if (%field% < 0) {#<span class="glyphicon glyphicon-arrow-down" aria-hidden="true"></span>#} else if (%field% > 0) {#<span class="glyphicon glyphicon-arrow-up" aria-hidden="true"></span>#}#' . $this->_format;
        }

        if (isset($options['colorize']) && $options['colorize']) {
            $this->_format = '<span class="#: %field% < 0 ? "negative" : (%field% > 0 ? "positive" : "neutral") #">' . $this->_format . '</span>';
        }

        parent::__construct($options, $tableId);
    }
}
