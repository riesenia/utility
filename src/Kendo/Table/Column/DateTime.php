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
    protected $_template = '<td class="tableColumn tableDate tableDateTime">kendo.toString(%field%, "g")</td>';
}
