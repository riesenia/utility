<?php
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
