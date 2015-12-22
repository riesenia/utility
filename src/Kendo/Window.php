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
     * Construct the window
     *
     * @param string id
     */
    public function __construct($id)
    {
        parent::__construct($id);

        $this->widget = Kendo::createWindow('#' . $id)
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
        return $this->_div($this->_id);
    }

    /**
     * Return JavaScript
     *
     * @return string
     */
    public function script()
    {
        $script = $this->widget->__toString();

        // center window on open
        $script .= '$("#' . $this->_id . '").data("' . $this->widget->name() . '").bind("open", function (e) {
            $("#' . $this->_id . '").data("' . $this->widget->name() . '").center();
        });';

        // center window on resize
        $script .= '$(window).resize(function (e) {
            $("#' . $this->_id . '").data("' . $this->widget->name() . '").center();
        });';

        // define global function for loading content and opening window
        $script .= 'window.' . $this->_id . 'Open = function (title, url) {
            $("#' . $this->_id . '").data("' . $this->widget->name() . '").title(title);

            $.get(url, {}, function (data) {
                $("#' . $this->_id . '").data("' . $this->widget->name() . '").content(data).center().open();
            });

            return false;
        };';

        return $script;
    }
}
