<?php

namespace Riesenia\Utility\Kendo;

use Riesenia\Kendo\Kendo;

/**
 * Tree helper
 *
 * @author Tomas Saghy <segy@riesenia.com>
 */
class Tree extends KendoHelper
{
    /**
     * Row template
     *
     * @var string
     */
    protected $_rowTemplate;

    /**
     * Table columns
     *
     * @var array
     */
    protected $_columns;

    /**
     * Actions
     *
     * @var array
     */
    protected $_actions;

    /**
     * Construct the tree
     *
     * @param string id
     */
    public function __construct($id)
    {
        parent::__construct($id);

        $this->model = Kendo::createModel()
            ->setId('id')
            ->setHasChildren('hasChildren');

        $this->dataSource = Kendo::createHierarchicalDataSource()
            ->setSchema([
                'model' => $this->model,
                'parse' => Kendo::js('function (data) {
                    // take results key
                    data = data["results"];

                    // prevent duplicates
                    var ids = $("#' . $id . '").data("ids");
                    if (typeof ids === "undefined")
                        ids = [];

                    ret = [];

                    for (var i in data) {
                        if ($.inArray(data[i].id, ids) > -1)
                            continue;

                        ids.push(data[i].id);
                        ret.push(data[i]);
                    }
                    $("#' . $id . '").data("ids", ids);

                    return ret;
                }')
            ]);

        $this->_widget = Kendo::createTreeView('#' . $id)
            ->setDataSource($this->dataSource)
            ->setDataTextField('name');
    }

    /**
     * Add field (passed to model)
     *
     * @param string field name
     * @param array parameters
     * @return Riesenia\Utility\Kendo\Tree
     */
    public function addField($key, $value = [])
    {
        $this->model->addField($key, $value);

        return $this;
    }

    /**
     * Add hasChildren field to model
     *
     * @param string field name
     * @return Riesenia\Utility\Kendo\Tree
     */
    public function hasChildren($field = 'hasChildren')
    {
        $this->model->setHasChildren($field)
            ->addField($field, [
                'type' => 'boolean',
                'parse' => Kendo::js('function (d) {
                    return d > 0;
                }')
            ]);

        return $this;
    }

    /**
     * Add transport (passed to datasource)
     *
     * @param string type
     * @param array options
     * @return Riesenia\Utility\Kendo\Tree
     */
    public function addTransport($type, $options = [])
    {
        $this->dataSource->addTransport($type, $options);

        return $this;
    }

    /**
     * Add checkboxes
     *
     * @param mixed checkboxes options
     * @return Riesenia\Utility\Kendo\Tree
     */
    public function addCheckboxes($options = null)
    {
        // default template
        if (is_null($options)) {
            $options = ['template' => '<input type="checkbox" value="#: item.id #" />'];
        }

        $this->_widget->setCheckboxes($options);

        return $this;
    }

    /**
     * Return HTML
     *
     * @return string
     */
    public function html()
    {
        return '<div id="' . $this->_id . '"></div>';
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
