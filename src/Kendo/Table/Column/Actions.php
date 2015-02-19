<?php
namespace Riesenia\Utility\Kendo\Table\Column;

/**
 * Actions column
 *
 * @author Tomas Saghy <segy@riesenia.com>
 */
class Actions extends Base
{
    /**
     * Predefined class
     *
     * @var string
     */
    protected $_class = 'tableColumn tableActions';

    /**
     * Construct the column
     *
     * @param string id
     * @param \Riesenia\Utility\Kendo\Table
     */
    public function __construct(array $options, $tableId)
    {
        parent::__construct($options, $tableId);

        // add command
        $this->_options['command'] = [];

        foreach ($this->_options['actions'] as $action) {
            $this->_options['command'][] = $action->command();
        }
    }

    /**
     * Column defintion in a grid row template
     *
     * @return string
     */
    public function __toString()
    {
        return '<td class="' . $this->_options['class'] . '">' . implode(' ', $this->_options['actions']) . '</td>';
    }

    /**
     * Return rendered javascript
     *
     * @return string
     */
    public function script()
    {
        $script = '';

        // add column scripts
        foreach ($this->_options['actions'] as $action) {
            $script .= $action->script();
        }

        return $script;
    }
}
