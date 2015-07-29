<?php
namespace Riesenia\Utility\Kendo;

use Riesenia\Kendo\Kendo;

/**
 * ListView helper
 *
 * @author Tomas Saghy <segy@riesenia.com>
 */
class ListView extends KendoHelper
{
    /**
     * Construct the list view
     *
     * @param string id
     */
    public function __construct($id)
    {
        parent::__construct($id);

        $this->model = Kendo::createModel()
            ->setId('id');

        $this->dataSource = Kendo::createDataSource()
            ->setSchema(['model' => $this->model, 'data' => 'results', 'total' => 'count'])
            ->setServerFiltering(true)
            ->setServerSorting(true)
            ->setServerPaging(true);

        $this->_widget = Kendo::createListView('#' . $id)
            ->setDataSource($this->dataSource);
    }

    /**
     * Add transport (passed to datasource)
     *
     * @param string template id
     * @return Riesenia\Utility\Kendo\ListView
     */
    public function setTemplateById($id)
    {
        $this->_widget->setTemplate(Kendo::js('kendo.template($("#' . $id . '").html())'));

        return $this;
    }

    /**
     * Return HTML
     *
     * @return string
     */
    public function html()
    {
        return $this->_div($this->_id);
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
