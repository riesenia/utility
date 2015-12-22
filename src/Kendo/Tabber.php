<?php
namespace Riesenia\Utility\Kendo;

use Riesenia\Kendo\Kendo;

/**
 * Tabber helper
 *
 * @author Tomas Saghy <segy@riesenia.com>
 */
class Tabber extends KendoHelper
{
    /**
     * List content
     *
     * @var string
     */
    protected $_ulContent = '';

    /**
     * Construct the tabber
     *
     * @param string id
     */
    public function __construct($id)
    {
        parent::__construct($id);

        $this->widget = Kendo::createTabStrip('#' . $id);
    }

    /**
     * Add remote tab
     *
     * @param string tab content
     * @param string url
     * @param bool active tab
     * @return Riesenia\Utility\Kendo\Tabber
     */
    public function addRemoteTab($content, $url, $active = false)
    {
        $this->_ulContent .= '<li' . ($active ? ' class="k-state-active"' : '') . '>' . $content . '</li>';

        $this->widget->addContentUrls(null, $url);

        return $this;
    }

    /**
     * Return HTML
     *
     * @return string
     */
    public function html()
    {
        return $this->_div($this->_id, '<ul>' . $this->_ulContent . '</ul>');
    }

    /**
     * Return JavaScript
     *
     * @return string
     */
    public function script()
    {
        $script = $this->widget->__toString();

        return $script;
    }
}
