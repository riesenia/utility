<?php
namespace Riesenia\Utility\Kendo;

/**
 * Base class for Kendo helpers
 *
 * @author Tomas Saghy <segy@riesenia.com>
 */
abstract class KendoHelper
{
    /**
     * Id of the main element
     *
     * @var string
     */
    protected $_id;

    /**
     * Kendo Model
     *
     * @var Riesenia\Kendo\Widget\Model
     */
    public $model = null;

    /**
     * Kendo data source
     *
     * @var Riesenia\Kendo\Widget\DataSource
     */
    public $dataSource = null;

    /**
     * Kendo widget
     *
     * @var Riesenia\Kendo\Widget\Base
     */
    protected $_widget = null;

    /**
     * Class aliases
     *
     * @var array
     */
    protected static $_aliases = [];

    /**
     * Construct the helper
     *
     * @param string id
     */
    public function __construct($id)
    {
        $this->_id = $id;
    }

    /**
     * Return new instance
     *
     * @param string id
     * @return Riesenia\Utility\Kendo\KendoHelper
     */
    public static function create($id)
    {
        return new static($id);
    }

    /**
     * Define class alias
     *
     * @param string alias
     * @param string full class name
     * @return void
     */
    public static function alias($alias, $class)
    {
        static::$_aliases[$alias] = $class;
    }

    /**
     * Return rendered HTML
     *
     * @return string
     */
    abstract public function html();

    /**
     * Return rendered javascript
     *
     * @return string
     */
    abstract public function script();

    /**
     * Add transport (passed to datasource)
     *
     * @param string type
     * @param array options
     * @return Riesenia\Utility\Kendo\KendoHelper
     */
    public function addTransport($type, $options = [])
    {
        $this->dataSource->addTransport($type, $options);

        return $this;
    }

    /**
     * Add field (passed to model)
     *
     * @param string field name
     * @param array options
     * @return Riesenia\Utility\Kendo\Tree
     */
    public function addField($key, $options = [])
    {
        $this->model->addField($key, $options);

        return $this;
    }

    /**
     * Output HTML on echoing
     *
     * @return string
     */
    public function __toString()
    {
        return $this->html();
    }

    /**
     * Handle dynamic method calls - forward them to the widget
     *
     * @param string method name
     * @param array arguments
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        if (is_null($this->_widget)) {
            throw new \BadMethodCallException("Unknown method: " . $method);
        }

        $return = call_user_func_array([$this->_widget, $method], $arguments);

        if (gettype($return) == 'object' && get_class($return) == get_class($this->_widget)) {
            return $this;
        }

        return $return;
    }
}
