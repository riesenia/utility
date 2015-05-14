<?php
namespace Riesenia\Utility\Kendo\Table\Column;

/**
 * Table checkbox column
 *
 * @author Tomas Saghy <segy@riesenia.com>
 */
class TableCheckbox extends Base
{
    /**
     * Column template with %field% placeholder
     *
     * @var string
     */
    protected $_template = '<td class="%class%"><input type="checkbox" value="#: %field% #" name="tableCheckbox" /></td>';

    /**
     * Predefined class
     *
     * @var string
     */
    protected $_class = 'tableColumn tableCheckbox';

    /**
     * Construct the column
     *
     * @param array options
     * @param string table id
     */
    public function __construct(array $options, $tableId)
    {
        parent::__construct($options, $tableId);

        // default field is id
        if (!isset($this->_options['field'])) {
            $this->_options['field'] = 'id';
        }
    }
}
