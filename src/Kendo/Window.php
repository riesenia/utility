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

        // center window on open
        $script .= '$("#' . $this->_id . '").data("kendoWindow").bind("open", function (e) {
            $("#' . $this->_id . '").data("kendoWindow").center();
        });';

        // center window on resize
        $script .= '$(window).resize(function (e) {
            $("#' . $this->_id . '").data("kendoWindow").center();
        });';

        // define global function for loading content and opening window
        $script .= 'window.' . $this->_id . 'Open = function (title, url) {
            $("#' . $this->_id . '").data("kendoWindow").title(title);
            $.get(url, {}, function (data) {
                $("#' . $this->_id . '").data("kendoWindow").content(data).center().open();
            });
        };';

        return $script;
    }
}
