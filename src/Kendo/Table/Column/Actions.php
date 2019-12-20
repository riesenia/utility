<?php
/**
 * This file is part of riesenia/utility package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */

declare(strict_types=1);

namespace Riesenia\Utility\Kendo\Table\Column;

/**
 * Actions column.
 *
 * @author Tomas Saghy <segy@riesenia.com>
 */
class Actions extends Base
{
    /**
     * Predefined class.
     *
     * @var string
     */
    protected $_class = 'tableColumn tableActions';

    /**
     * Predefined style.
     *
     * @var string
     */
    protected $_style = '';

    /**
     * Construct the column.
     *
     * @param array  $options
     * @param string $tableId
     */
    public function __construct(array $options, string $tableId)
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
     * Return rendered javascript.
     *
     * @return string
     */
    public function script(): string
    {
        $script = '';

        // add column scripts
        foreach ($this->_options['actions'] as $action) {
            $script .= $action->script();
        }

        return $script;
    }

    /**
     * Return rendered column.
     *
     * @return string
     */
    public function __toString(): string
    {
        return '<td class="' . $this->_options['class'] . '" style="' . $this->_style . '">' . \implode(' ', $this->_options['actions']) . '</td>';
    }
}
