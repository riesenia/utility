<?php
namespace Riesenia\Utility\Kendo;

use Riesenia\Kendo\Kendo;

/**
 * Select helper
 *
 * @author Tomas Saghy <segy@riesenia.com>
 */
class Select extends KendoHelper
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

        $this->_widget = Kendo::createDropDownList('#' . $id)
            ->setDataSource($this->dataSource)
            ->setDataValueField('id')
            ->setDataTextField('name');

        $this->setAttribute('name', $id);
    }

    /**
     * Return HTML
     *
     * @return string
     */
    public function html()
    {
        return $this->_input($this->_id);
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
