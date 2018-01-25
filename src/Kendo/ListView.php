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
 * ListView helper.
 *
 * @author Tomas Saghy <segy@riesenia.com>
 */
class ListView extends KendoHelper
{
    /**
     * Construct the list view.
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

        $this->widget = Kendo::createListView('#' . $id)
            ->setDataSource($this->dataSource);
    }

    /**
     * Set template providing only id.
     *
     * @param string $id
     *
     * @return $this
     */
    public function setTemplateById(string $id): self
    {
        $this->widget->setTemplate(Kendo::js('kendo.template($("#' . $id . '").html())'));

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
        $script = $this->widget->__toString();

        return $script;
    }
}
