<?php

namespace Riesenia\Utility\Kendo;

use Riesenia\Kendo\Kendo;

/**
 * Table helper
 *
 * @author Tomas Saghy <segy@riesenia.com>
 */
class Table extends KendoHelper
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
     * Text for no results
     *
     * @var string
     */
    protected $_noResults;

    /**
     * Construct the table
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

        $this->_widget = Kendo::createGrid('#' . $this->_id)
            ->setDataSource($this->dataSource)
            ->setSortable(['allowUnsort' => false]);

        $this->_rowTemplate = '<tr data-uid="#: uid #">';
        $this->_columns = [];
        $this->_actions = [];
    }

    /**
     * Add transport (passed to datasource)
     *
     * @param string type
     * @param array options
     * @return Riesenia\Utility\Kendo\Table
     */
    public function addTransport($type, $options = [])
    {
        $this->dataSource->addTransport($type, $options);

        return $this;
    }

    /**
     * Add table column
     *
     * @param string field name
     * @param string column title
     * @param string type
     * @param array options
     * @return Riesenia\Utility\Kendo\Table
     */
    public function addColumn($field, $title = '&nbsp;', $type = null, $options = [])
    {
        // resolve alias
        if (isset(static::$_aliases[$type])) {
            $type = static::$_aliases[$type];
        }

        // type can be a name of user defined class
        if (!class_exists($type) || !is_subclass_of($type, __NAMESPACE__ . '\\Table\\Column\\Base')) {
            // default class
            if (is_null($type)) {
                $type = 'base';
            }

            $type = __NAMESPACE__ . '\\Table\\Column\\' . ucfirst($type);

            if (!class_exists($type)) {
                throw new \BadMethodCallException("Invalid column class: " . $type);
            }
        }

        // field and title
        $options['field'] = $field;
        $options['title'] = $title;

        // create column class instance
        $column = new $type($options, $this->_id);

        if ($field) {
            $this->model->addField($field, $column->getModelOptions());
        }

        $this->_widget->addColumns(null, $column->getColumnOptions());

        $this->_columns[] = $column;

        return $this;
    }

    /**
     * Add table action
     *
     * @param string type
     * @param array options
     * @return Riesenia\Utility\Kendo\Table
     */
    public function addAction($type = null, $options = [])
    {
        // resolve alias
        if (isset(static::$_aliases[$type])) {
            $type = static::$_aliases[$type];
        }

        // type can be a name of user defined class
        if (!class_exists($type) || !is_subclass_of($type, __NAMESPACE__ . '\\Table\\Action\\Base')) {
            // default class
            if (is_null($type)) {
                $type = 'base';
            }

            $type = __NAMESPACE__ . '\\Table\\Action\\' . ucfirst($type);

            if (!class_exists($type)) {
                throw new \BadMethodCallException("Invalid action class: " . $type);
            }
        }

        // create action class instance
        $action = new $type($options, $this->_id);

        $this->_actions[] = $action;

        return $this;
    }

    /**
     * Set no results text
     *
     * @param string
     * @return Riesenia\Utility\Kendo\Table
     */
    public function setNoResults($text) {
        $this->_noResults = $text;

        return $this;
    }

    /**
     * Set no results text
     *
     * @param string
     * @return Riesenia\Utility\Kendo\Table
     */
    public function setRowTemplate($template) {
        $this->_rowTemplate = $template;

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
        // define actions
        if (count($this->_actions)) {
            $this->addColumn(null, '&nbsp;', 'actions', ['actions' => $this->_actions]);
        }

        // complete row template
        $this->_widget->setRowTemplate($this->_rowTemplate . implode('', $this->_columns) . '</tr>');

        $script = $this->_widget->__toString();

        // handle empty result set
        if ($this->_noResults) {
            $script .= '$(function() {
                $("#' . $this->_id . '").data("kendoGrid").bind("dataBound", function(e) {
                    if (!e.sender.dataSource.view().length) {
                        e.sender.tbody.append("<tr><td colspan=\"' . count($this->_columns) . '\" align=\"center\">' . $this->_noResults . '</td></tr>");
                    }
                });
            });';
        }

        // add column scripts
        foreach ($this->_columns as $column) {
            $script .= $column->script();
        }

        return $script;
    }
}
