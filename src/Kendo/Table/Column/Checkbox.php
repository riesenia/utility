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
 * Checkbox column.
 *
 * @author Tomas Saghy <segy@riesenia.com>
 */
class Checkbox extends Input
{
    /** @var string */
    protected $_type = 'boolean';

    /** @var string */
    protected $_template = '<td class="%class%" style="%style%"><div><input type="checkbox" data-row-uid="#: uid #" name="%field%Input" # if (%field%) { # checked="checked" # } # /></div></td>';

    /** @var string */
    protected $_class = 'tableColumn tableCheckbox';

    /**
     * {@inheritDoc}
     */
    protected function _setValue(): string
    {
        return '!item.' . $this->_options['field'];
    }
}
