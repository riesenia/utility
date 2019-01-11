<?php
/**
 * This file is part of riesenia/utility package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */

declare(strict_types=1);

namespace Riesenia\Utility\Kendo;

/**
 * Base class for Kendo helpers.
 *
 * @author Tomas Saghy <segy@riesenia.com>
 */
abstract class KendoHelper
{
    /**
     * Kendo Model.
     *
     * @var \Riesenia\Kendo\Widget\Model
     */
    public $model;

    /**
     * Kendo data source.
     *
     * @var \Riesenia\Kendo\Widget\DataSource
     */
    public $dataSource;

    /**
     * Kendo widget.
     *
     * @var \Riesenia\Kendo\Widget\Base
     */
    public $widget;

    /**
     * Id of the main element.
     *
     * @var string
     */
    protected $_id;

    /**
     * HTML attributes.
     *
     * @var array
     */
    protected $_htmlAttributes = [];

    /**
     * Class aliases.
     *
     * @var array
     */
    protected static $_aliases = [];

    /**
     * Construct the helper.
     *
     * @param string $id
     */
    public function __construct(string $id)
    {
        $this->_id = $id;
    }

    /**
     * Return new instance.
     *
     * @param string $id
     *
     * @return static
     */
    public static function create(string $id): self
    {
        return new static($id);
    }

    /**
     * Define class alias.
     *
     * @param string $alias
     * @param string $class
     */
    public static function alias(string $alias, string $class)
    {
        static::$_aliases[$alias] = $class;
    }

    /**
     * Return rendered HTML.
     *
     * @return string
     */
    abstract public function html(): string;

    /**
     * Return rendered javascript.
     *
     * @return string
     */
    abstract public function script(): string;

    /**
     * Add transport (passed to datasource).
     *
     * @param string $type
     * @param array  $options
     *
     * @return $this
     */
    public function addTransport(string $type, array $options = []): self
    {
        $this->dataSource->addTransport($type, $options);

        return $this;
    }

    /**
     * Add field (passed to model).
     *
     * @param string $key
     * @param array  $options
     *
     * @return $this
     */
    public function addField(string $key, array $options = []): self
    {
        $this->model->addField($key, $options);

        return $this;
    }

    /**
     * Add HTML attribute.
     *
     * @param string $name
     * @param mixed  $value
     *
     * @return $this
     */
    public function addAttribute(string $name, $value): self
    {
        $this->_htmlAttributes[$name] = $value;

        return $this;
    }

    /**
     * Render input.
     *
     * @param string $id
     *
     * @return string
     */
    protected function _input(string $id): string
    {
        return $this->_tag('input', false, array_merge($this->_htmlAttributes, ['id' => $id]));
    }

    /**
     * Render select.
     *
     * @param string $id
     *
     * @return string
     */
    protected function _select(string $id): string
    {
        return $this->_tag('select', '', array_merge($this->_htmlAttributes, ['id' => $id]));
    }

    /**
     * Render div.
     *
     * @param string $id
     * @param string $content
     *
     * @return string
     */
    protected function _div(string $id, string $content = ''): string
    {
        return $this->_tag('div', $content, array_merge($this->_htmlAttributes, ['id' => $id]));
    }

    /**
     * Render HTML tag.
     *
     * @param string      $name
     * @param bool|string $content
     * @param array       $attributes
     *
     * @return string
     */
    protected function _tag(string $name, $content = false, array $attributes = []): string
    {
        $tag = '<' . $name;

        foreach ($attributes as $attribute => $value) {
            if ($value !== null && $value !== false) {
                $tag .= ' ' . $attribute . '="' . ($value === true ? $attribute : $value) . '"';
            }
        }

        $tag .= $content === false ? ' />' : '>' . $content . '</' . $name . '>';

        return $tag;
    }

    /**
     * Output HTML on echoing.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->html();
    }

    /**
     * Handle dynamic method calls - forward them to the widget.
     *
     * @param string $method
     * @param array  $arguments
     *
     * @return mixed
     */
    public function __call(string $method, array $arguments)
    {
        if ($this->widget === null) {
            throw new \BadMethodCallException('Unknown method: ' . $method);
        }

        $return = $this->widget->{$method}(...$arguments);

        if (gettype($return) == 'object' && get_class($return) == get_class($this->widget)) {
            return $this;
        }

        return $return;
    }
}
