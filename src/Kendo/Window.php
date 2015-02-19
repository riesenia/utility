<?php

namespace Riesenia\Utility\Kendo;

use Riesenia\Kendo\Kendo;

/**
 * Window helper
 *
 * @author Tomas Saghy <segy@riesenia.com>
 */
class Window extends KendoHelper
{
    /**
     * Construct the tree
     *
     * @param string id
     */
    public function __construct($id)
    {
        parent::__construct($id);

        $this->_widget = Kendo::createWindow('#' . $id)
            ->setWidth(700)
            ->setModal(true)
            ->setResizable(false)
            ->setVisible(false);
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
