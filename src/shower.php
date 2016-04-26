<?php
/**
 * Created by PhpStorm.
 * User: kingmax
 * Date: 16/2/27
 * Time: 下午4:21
 */

namespace rayful\Tool;


trait shower
{
    protected $_key;
    protected $_value;

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Retrieve an external iterator
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return \Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     */
    public function getIterator()
    {
        $iterator = new \ArrayIterator($this);
        foreach ($iterator as $key => $value) {
            $Property = new ReflectionPropertyX($this, $key);
            if ($key == "_id" || stripos($Property->_ignore, "show") !== false) continue;

            $this->_key = $key;
            $this->_value = $value;
            $var_type = strtolower($Property->_var);        //@var
            $method1 = $key . "_text";
            $method2 = $key;

            if (method_exists($this, $method1)) {
                $content = $this->{$method1} ();
            } elseif (method_exists($this, $method2)) {
                $content = $this->{$method2} ();
            } else {
                if ($var_type == "bool" || $var_type == "boolean") {
                    $content = $this->_show_bool($value);    //PHPDoc里面预定义了@var为boolean的
                } elseif ($var_type == "array" || preg_match('/\[\]/', $var_type)) {
                    $content = $this->_show_array($value);    //PHPDoc里面预定义了@var为array的
                } elseif ($var_type == "file") {
                    $content = $this->_show_file($value);
                } else {
                    $content = $this->_show_string($value);
                }
            }

            $name = $Property->_name ?: $key;
            yield $name => $content;
        }
    }

    protected function _show_bool($value)
    {
        return $value ? "是" : "否";
    }

    protected function _show_array($value)
    {
        ob_start();
        print_r($value);
        return ob_get_clean();
    }

    protected function _show_file($value)
    {
        return "下载文件";//@TODO 补充完成,因为暂时未有此种类型
    }

    protected function _show_string($value)
    {
        return $value;
    }

    public function get_key()
    {
        return $this->_key;
    }

    public function get_value()
    {
        return $this->_value;
    }
}