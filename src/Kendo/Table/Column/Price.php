<?php
namespace Riesenia\Utility\Kendo\Table\Column;

/**
 * Price column
 *
 * @author Tomas Saghy <segy@riesenia.com>
 */
class Price extends Number
{
    /**
     * Column template with %field% placeholder
     *
     * @var string
     */
    protected $_template = '<td class="%class%">#: kendo.toString(%field%, "c2") #</td>';

    /**
     * Predefined class
     *
     * @var string
     */
    protected $_class = 'tableColumn tableNumber tablePrice';
}
