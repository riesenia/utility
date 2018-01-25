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
    /**
     * Options.
     *
     * @var array
     */
    protected $_options = [
        'class' => 'k-grid-delete',
        'link' => '\#',
        'title' => 'Delete',
        'target' => '_self',
        'icon' => 'remove'
    ];

    /**
     * Command (for column definition).
     *
     * @return mixed
     */
    public function command()
    {
        return 'destroy';
    }
}
