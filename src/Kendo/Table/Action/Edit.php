<?php
namespace Riesenia\Utility\Kendo\Table\Action;

/**
 * Edit action
 *
 * @author Tomas Saghy <segy@riesenia.com>
 */
class Edit extends Base
{
    /**
     * Options
     *
     * @var array
     */
    protected $_options = [
        'class' => 'k-grid-edit',
        'link' => '\#',
        'title' => 'Edit',
        'target' => '_self',
        'icon' => 'edit'
    ];

    /**
     * Command (for column definition)
     *
     * @return mixed
     */
    public function command()
    {
        return 'edit';
    }
}
