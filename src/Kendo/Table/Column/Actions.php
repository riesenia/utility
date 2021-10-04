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
    /** @var string */
    protected $_class = 'tableColumn tableActions';

    /** @var string */
    protected $_style = '';

    /**
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
     */
    public function __toString(): string
    {
        return '<td class="' . $this->_options['class'] . '" style="' . $this->_style . '">' . \implode(' ', $this->_options['actions']) . '</td>';
    }
}
