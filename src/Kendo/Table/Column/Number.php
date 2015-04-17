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
}
