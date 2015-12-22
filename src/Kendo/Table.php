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
    protected $_rowTemplate = '<tr data-uid="#: uid #" class="%class%">';

    /**
     * Row classes
     *
     * @var array
     */
    protected $_rowClasses = [];

    /**
     * Table columns
     *
     * @var array
     */
    protected $_columns = [];

    /**
     * Actions
     *
     * @var array
     */
    protected $_actions = [];

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

        $this->widget = Kendo::createGrid('#' . $this->_id)
            ->setDataSource($this->dataSource)
            ->setSortable(['allowUnsort' => false]);
    }

    /**
     * Set detailInit property
     *
     * @param \Riesenia\Kendo\JavascriptFunction
     * @return Riesenia\Utility\Kendo\Table
     */
    public function setDetailInit($value)
    {
        $this->addColumn(null, '&nbsp;', 'hierarchyCell', [], true, false);
        $this->addRowClass('k-master-row');
        $this->widget->setDetailInit($value);

        return $this;
    }

    /**
     * Add checkboxes
     *
     * @param array options
     * @return Riesenia\Utility\Kendo\Table
     */
    public function addCheckboxes($options = [])
    {
        $this->addColumn(null, '&nbsp;', 'tableCheckbox', $options, true);

        return $this;
    }

    /**
     * Add table column
     *
     * @param string field name
     * @param string column title
     * @param string type
     * @param array options
     * @param bool prepend
     * @param bool add widget column
     * @return Riesenia\Utility\Kendo\Table
     */
    public function addColumn($field, $title = '&nbsp;', $type = null, $options = [], $prepend = false, $addWidgetColumn = true)
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

        if ($addWidgetColumn) {
            $prepend ? $this->widget->setColumns(array_merge([$column->getColumnOptions()], is_array($this->widget->getColumns()) ? $this->widget->getColumns() : [])) : $this->widget->addColumns(null, $column->getColumnOptions());
        }

        $prepend ? array_unshift($this->_columns, $column) : array_push($this->_columns, $column);

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
    public function setNoResults($text)
    {
        $this->_noResults = $text;

        return $this;
    }

    /**
     * Set no results text
     *
     * @param string
     * @return Riesenia\Utility\Kendo\Table
     */
    public function setRowTemplate($template)
    {
        $this->_rowTemplate = $template;

        return $this;
    }

    /**
     * Add row class
     *
     * @param string
     * @return Riesenia\Utility\Kendo\Table
     */
    public function addRowClass($class)
    {
        $this->_rowClasses[] = $class;

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
        // define actions
        if (count($this->_actions)) {
            $this->addColumn(null, '&nbsp;', 'actions', ['actions' => $this->_actions]);
        }

        // complete row template
        $this->_rowTemplate = str_replace('%class%', implode(' ', $this->_rowClasses), $this->_rowTemplate);
        $this->widget->setRowTemplate($this->_rowTemplate . implode('', $this->_columns) . '</tr>');

        $script = $this->widget->__toString();

        // handle empty result set
        if ($this->_noResults) {
            $script .= '$(function() {
                $("#' . $this->_id . '").data("' . $this->widget->name() . '").bind("dataBound", function(e) {
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
