<?php
namespace Riesenia\Utility\Kendo\Table\Column;

/**
 * Checkbox column.
 *
 * @author Tomas Saghy <segy@riesenia.com>
 */
class Checkbox extends Input
{
    /**
     * Type used for model type property.
     *
     * @var string
     */
    protected $_type = 'boolean';

    /**
     * Column template with %field% placeholder.
     *
     * @var string
     */
    protected $_template = '<td class="%class%" style="%style%"><input type="checkbox" data-row-uid="#: uid #" name="%field%Input" # if (%field%) { # checked="checked" # } # /></td>';

    /**
     * Predefined class.
     *
     * @var string
     */
    protected $_class = 'tableColumn tableCheckbox';

    /**
     * Value setter.
     *
     * @var string
     */
    protected function _setValue()
    {
        return '!item.' . $this->_options['field'];
    }
}
