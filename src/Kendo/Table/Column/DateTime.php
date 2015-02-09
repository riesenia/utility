<?php
namespace Riesenia\Utility\Kendo\Table\Column;

/**
 * Date column
 *
 * @author Tomas Saghy <segy@riesenia.com>
 */
class DateTime extends Date
{
    /**
     * Column template with %field% placeholder
     *
     * @var string
     */
    protected $_template = '<td class="%class%">kendo.toString(%field%, "g")</td>';

    /**
     * Predefined class
     *
     * @var string
     */
    protected $_class = 'tableColumn tableDate tableDateTime';
}
