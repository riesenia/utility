<?php
namespace Riesenia\Utility\Kendo;

use Riesenia\Kendo\Kendo;

/**
 * Tree helper.
 *
 * @author Tomas Saghy <segy@riesenia.com>
 */
class Tree extends KendoHelper
{
    /**
     * Construct the tree.
     *
     * @param string $id
     */
    public function __construct($id)
    {
        parent::__construct($id);

        $this->model = Kendo::createModel()
            ->setId('id');

        $this->dataSource = Kendo::createHierarchicalDataSource()
            ->setSchema(['model' => $this->model, 'data' => 'results', 'total' => 'count']);

        $this->widget = Kendo::createTreeView('#' . $id)
            ->setDataSource($this->dataSource)
            ->setDataTextField('name');
    }

    /**
     * Add hasChildren field to model.
     *
     * @param string $field
     *
     * @return $this
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
     * Add checkboxes.
     *
     * @param mixed|null $options
     *
     * @return $this
     */
    public function addCheckboxes($options = null)
    {
        // default template
        if ($options === null) {
            $options = ['template' => '<input type="checkbox" name="tableCheckbox" value="#: item.id #" />'];
        }

        $this->widget->setCheckboxes($options);

        return $this;
    }

    /**
     * Expand tree through provided path.
     *
     * @param array $path from highest node to the last
     *
     * @return $this
     */
    public function expand($path = [])
    {
        $this->widget->setDataBound(Kendo::js('function (e) {
            var expand = $("#' . $this->_id . '").data("expand");

            // initial
            if (typeof expand === "undefined") {
                expand = ' . json_encode($path) . ';
            }

            if (expand.length) {
                var id = expand.shift();

                // select if last node or expand this node
                var node = this.findByUid(this.dataSource.get(id).uid);
                if (!expand.length) {
                    this.select(node);
                } else {
                    this.expand(node);
                }

                $("#' . $this->_id . '").data("expand", expand);
            }
        }'));

        return $this;
    }

    /**
     * Return HTML.
     *
     * @return string
     */
    public function html()
    {
        return $this->_div($this->_id);
    }

    /**
     * Return JavaScript.
     *
     * @return string
     */
    public function script()
    {
        $script = $this->widget->__toString();

        return $script;
    }
}
