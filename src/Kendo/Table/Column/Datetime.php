<?php
namespace Riesenia\Utility\Kendo\Table\Column;

/**
 * Datetime column
 *
 * @author Tomas Saghy <segy@riesenia.com>
 */
class Datetime extends Date
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
    protected $_class = 'tableColumn tableDate tableDatetime';
}
