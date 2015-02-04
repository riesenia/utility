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
    protected $_template = '<td class="tableColumn tableNumber tablePrice">#: kendo.toString(%field%, "c2") #</td>';
}
