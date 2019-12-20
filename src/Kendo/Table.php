<?php
/**
 * This file is part of riesenia/utility package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */

declare(strict_types=1);

namespace Riesenia\Utility\Kendo;

use Riesenia\Kendo\Kendo;

/**
 * Table helper.
 *
 * @author Tomas Saghy <segy@riesenia.com>
 */
class Table extends KendoHelper
{
    /**
     * Row template.
     *
     * @var string
     */
    protected $_rowTemplate = '<tr data-uid="#: uid #" class="%class%" style="%style%">';

    /**
     * Row classes.
     *
     * @var array
     */
    protected $_rowClasses = [];

    /**
     * Row styles.
     *
     * @var array
     */
    protected $_rowStyles = [];

    /**
     * Table columns.
     *
     * @var array
     */
    protected $_columns = [];

    /**
     * Actions.
     *
     * @var array
     */
    protected $_actions = [];

    /**
     * Text for no results.
     *
     * @var string
     */
    protected $_noResults;

    /**
     * Width for one action button.
     *
     * @var int
     */
    protected $_actionWidth = 45;

    /**
     * Construct the table.
     *
     * @param string $id
     */
    public function __construct(string $id)
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
     * Set detailInit property.
     *
     * @param \Riesenia\Kendo\JavascriptFunction $value
     *
     * @return $this
     */
    public function setDetailInit($value): self
    {
        $this->addColumn(null, '&nbsp;', 'hierarchyCell', [], true, false);
        $this->addRowClass('k-master-row');
        $this->widget->setDetailInit($value);

        return $this;
    }

    /**
     * Add checkboxes.
     *
     * @param array $options
     *
     * @return $this
     */
    public function addCheckboxes(array $options = []): self
    {
        $this->addColumn(null, '&nbsp;', 'tableCheckbox', $options, true);

        return $this;
    }

    /**
     * Add table column.
     *
     * @param string|null $field
     * @param string      $title
     * @param string|null $type
     * @param array       $options
     * @param bool        $prepend
     * @param bool        $addWidgetColumn
     *
     * @return $this
     */
    public function addColumn(?string $field, string $title = '&nbsp;', string $type = null, array $options = [], bool $prepend = false, bool $addWidgetColumn = true): self
    {
        // resolve alias
        if (isset(static::$_aliases[$type])) {
            $type = static::$_aliases[$type];
        }

        // default class
        if ($type === null) {
            $type = 'base';
        }

        // type can be a name of user defined class
        if (!\class_exists($type) || !\is_subclass_of($type, __NAMESPACE__ . '\\Table\\Column\\Base')) {
            $type = __NAMESPACE__ . '\\Table\\Column\\' . \ucfirst($type);

            if (!\class_exists($type)) {
                throw new \BadMethodCallException('Invalid column class: ' . $type);
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
            $prepend ? $this->widget->setColumns(\array_merge([$column->getColumnOptions()], \is_array($this->widget->getColumns()) ? $this->widget->getColumns() : [])) : $this->widget->addColumns(null, $column->getColumnOptions());
        }

        $prepend ? \array_unshift($this->_columns, $column) : \array_push($this->_columns, $column);

        return $this;
    }

    /**
     * Add table action.
     *
     * @param string|null $type
     * @param array       $options
     *
     * @return $this
     */
    public function addAction(string $type = null, array $options = []): self
    {
        // resolve alias
        if (isset(static::$_aliases[$type])) {
            $type = static::$_aliases[$type];
        }

        // default class
        if ($type === null) {
            $type = 'base';
        }

        // type can be a name of user defined class
        if (!\class_exists($type) || !\is_subclass_of($type, __NAMESPACE__ . '\\Table\\Action\\Base')) {
            $type = __NAMESPACE__ . '\\Table\\Action\\' . \ucfirst($type);

            if (!\class_exists($type)) {
                throw new \BadMethodCallException('Invalid action class: ' . $type);
            }
        }

        // create action class instance
        $action = new $type($options, $this->_id);

        $this->_actions[] = $action;

        return $this;
    }

    /**
     * Set no results text.
     *
     * @param string $text
     *
     * @return $this
     */
    public function setNoResults(string $text): self
    {
        $this->_noResults = $text;

        return $this;
    }

    /**
     * Set no results text.
     *
     * @param string $template
     *
     * @return $this
     */
    public function setRowTemplate(string $template): self
    {
        $this->_rowTemplate = $template;

        return $this;
    }

    /**
     * Add row class.
     *
     * @param string $class
     *
     * @return $this
     */
    public function addRowClass(string $class): self
    {
        $this->_rowClasses[] = $class;

        return $this;
    }

    /**
     * Add row style.
     *
     * @param string $style
     *
     * @return $this
     */
    public function addRowStyle(string $style): self
    {
        $this->_rowStyles[] = $style;

        return $this;
    }

    /**
     * Add checkboxes.
     *
     * @param int $width
     *
     * @return $this
     */
    public function setActionWidth(int $width): self
    {
        $this->_actionWidth = $width;

        return $this;
    }

    /**
     * Return HTML.
     *
     * @return string
     */
    public function html(): string
    {
        return $this->_div($this->_id);
    }

    /**
     * Return JavaScript.
     *
     * @return string
     */
    public function script(): string
    {
        // define actions
        if (\count($this->_actions)) {
            $this->addColumn(null, '&nbsp;', 'actions', ['actions' => $this->_actions, 'width' => \count($this->_actions) * $this->_actionWidth]);
        }

        // complete row template
        $this->_rowTemplate = '# var grid = $("\\#' . $this->_id . '").data("kendoGrid"); #' . \str_replace(['%class%', '%style%'], [\implode(' ', $this->_rowClasses), \implode(' ', $this->_rowStyles)], $this->_rowTemplate);
        $this->widget->setRowTemplate($this->_rowTemplate . \implode('', $this->_columns) . '</tr>');

        $script = $this->widget->__toString();

        // handle empty result set
        if ($this->_noResults) {
            $script .= '$(function() {
                $("#' . $this->_id . '").data("' . $this->widget->name() . '").bind("dataBound", function(e) {
                    if (!e.sender.dataSource.view().length) {
                        e.sender.tbody.append("<tr><td colspan=\"' . \count($this->_columns) . '\" align=\"center\">' . $this->_noResults . '</td></tr>");

                        // checkbox
                        if ($("#' . $this->_id . ' [name=tableCheckboxAll]").length) {
                            $("#' . $this->_id . ' [name=tableCheckboxAll]").prop("checked", false).prop("disabled", true);
                        }
                    } else {
                        // checkbox
                        if ($("#' . $this->_id . ' [name=tableCheckboxAll]").length) {
                            $("#' . $this->_id . ' [name=tableCheckboxAll]").prop("disabled", false);
                        }
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
