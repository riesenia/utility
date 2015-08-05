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
     * Field format with %field% placeholder
     *
     * @var string
     */
    protected $_format = '#: kendo.toString(%field%, "g") #';

    /**
     * Predefined class
     *
     * @var string
     */
    protected $_class = 'tableColumn tableDate tableDateTime';
}
