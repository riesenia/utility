<?php
namespace Riesenia\Utility\Kendo;

use Riesenia\Kendo\Kendo;

/**
 * MultiSelect helper
 *
 * @author Tomas Saghy <segy@riesenia.com>
 */
class MultiSelect extends KendoHelper
{
    /**
     * Construct the select
     *
     * @param string id
     */
    public function __construct($id)
    {
        parent::__construct($id);

        $this->dataSource = Kendo::createDataSource()
            ->setSchema(['data' => 'results', 'total' => 'count'])
            ->setServerFiltering(true)
            ->setServerPaging(true);

        $this->_widget = Kendo::createMultiSelect('#' . $id)
            ->setDataSource($this->dataSource)
            ->setDataValueField('id')
            ->setDataTextField('name');

        $this->addAttribute('name', $id);
    }

    /**
     * Return HTML
     *
     * @return string
     */
    public function html()
    {
        return $this->_select($this->_id);
    }

    /**
     * Return JavaScript
     *
     * @return string
     */
    public function script()
    {
        $script = $this->_widget->__toString();

        return $script;
    }
}
