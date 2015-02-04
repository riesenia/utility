<?php
namespace Riesenia\Utility\Kendo\Table\Column;

/**
 * Date column
 *
 * @author Tomas Saghy <segy@riesenia.com>
 */
class Date extends Base
{
    /**
     * Type used for model type property
     *
     * @var string
     */
    protected $_type = 'date';

    /**
     * Column template with %field% placeholder
     *
     * @var string
     */
    protected $_template = '<td class="tableColumn tableDate">kendo.toString(%field%, "d")</td>';
}
