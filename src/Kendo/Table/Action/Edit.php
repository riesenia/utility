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
 * Edit action.
 *
 * @author Tomas Saghy <segy@riesenia.com>
 */
class Edit extends Base
{
    /** @var array<string,mixed> */
    protected $_options = [
        'class' => 'k-grid-edit',
        'link' => '\#',
        'title' => 'Edit',
        'target' => '_self',
        'icon' => 'edit'
    ];

    /**
     * {@inheritDoc}
     */
    public function command()
    {
        return 'edit';
    }
}
