<?php
/**
 * This file is part of riesenia/utility package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */

declare(strict_types=1);

namespace Riesenia\Utility\Kendo\Table\Action;

/**
 * Delete action.
 *
 * @author Tomas Saghy <segy@riesenia.com>
 */
class Delete extends Base
{
    /** @var array<string,mixed> */
    protected $_options = [
        'class' => 'k-grid-delete',
        'link' => '\#',
        'title' => 'Delete',
        'target' => '_self',
        'icon' => 'remove'
    ];

    /**
     * {@inheritDoc}
     */
    public function command()
    {
        return 'destroy';
    }
}
