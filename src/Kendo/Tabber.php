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
 * Tabber helper.
 *
 * @author Tomas Saghy <segy@riesenia.com>
 */
class Tabber extends KendoHelper
{
    /**
     * List content.
     *
     * @var string
     */
    protected $_ulContent = '';

    /**
     * {@inheritdoc}
     */
    public function __construct(string $id)
    {
        $this->_id = $id;

        $this->widget = Kendo::createTabStrip('#' . $id);
    }

    /**
     * Add remote tab.
     *
     * @param string $content
     * @param string $url
     * @param bool   $active
     *
     * @return $this
     */
    public function addRemoteTab(string $content, string $url, bool $active = false): self
    {
        $this->_ulContent .= '<li' . ($active ? ' class="k-state-active"' : '') . '>' . $content . '</li>';

        $this->widget->addContentUrls(null, $url);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function html(): string
    {
        return $this->_div($this->_id, '<ul>' . $this->_ulContent . '</ul>');
    }

    /**
     * {@inheritdoc}
     */
    public function script(): string
    {
        $script = $this->widget->__toString();

        return $script;
    }
}
