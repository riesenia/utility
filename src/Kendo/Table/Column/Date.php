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
     * Field format with %field% placeholder
     *
     * @var string
     */
    protected $_format = '#: kendo.toString(%field%, "d") #';

    /**
     * Predefined class
     *
     * @var string
     */
    protected $_class = 'tableColumn tableDate';
}
