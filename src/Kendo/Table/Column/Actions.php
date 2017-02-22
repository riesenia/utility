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
     * Predefined style
     *
     * @var string
     */
    protected $_style = '';

    /**
     * Construct the column
     *
     * @param array options
     * @param string table id
     */
    public function __construct(array $options, $tableId)
    {
        $this->_style = 'width: ' . $options['width'] . 'px;';

        parent::__construct($options, $tableId);

        // add command
        $this->_options['command'] = [];

        foreach ($this->_options['actions'] as $action) {
            $this->_options['command'][] = $action->command();
        }
    }

    /**
     * Return rendered column
     *
     * @return string
     */
    public function __toString()
    {
        return '<td class="' . $this->_options['class'] . '" style="' . $this->_style . '">' . implode(' ', $this->_options['actions']) . '</td>';
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
