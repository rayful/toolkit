<?php
/**
 * Created by PhpStorm.
 * User: kingmax
 * Date: 16/2/27
 * Time: 下午4:22
 */

namespace rayful\Tool;


trait editor
{

    protected $_key;
    protected $_value;

    /**
     * 必须实现这个方法，校验这条记录是否在数据库中存在
     * (一般在Data基类里面已经实现了这个方法)
     * @return boolean
     */
    abstract public function isExists();

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
            if (
                $key == "_id" ||
                preg_match('/edit/', $Property->_ignore) ||
                (preg_match('/new/', $Property->_ignore) && !$this->isExists())
            )
                continue;

            $this->_key = $key;
            $this->_value = $value;
            $defined_method = "input_" . $key;
            $var_type = strtolower($Property->_var);        //@var
            $input_type = strtolower($Property->_input);    //@input

            ob_start();
            if (method_exists($this, $defined_method)) {
                $this->{$defined_method}();
            } else {

                if ($input_type) { //PHPDoc里面预定义了@input 为select、checkbox、radio、textarea四种为有效类型
                    $input_method = "_input_" . $input_type;
                    if (method_exists($this, $input_method)) {
                        $this->{$input_method}($key, $value);
                    }
                } elseif ($var_type == "bool" || $var_type == "boolean") {
                    $this->_input_bool($key, $value);    //PHPDoc里面预定义了@var为boolean的
                } elseif ($var_type == "array" || preg_match('/\[\]/', $var_type)) {
                    $this->_input_array($key, $value);    //PHPDoc里面预定义了@var为array的
                } elseif ($var_type == "file") {
                    $this->_input_file($key, $value);
                } elseif ($var_type == "mongodate") {
                    $this->_input_date($key, $value);
                } else {
                    $this->_input_string($key, $value);
                }
                echo $this->_tips($key);
            }

            $content = ob_get_clean();
            $name = $Property->_name ?: $key;
            yield $name => $content;
        }
    }

    public function get_key()
    {
        return $this->_key;
    }

    public function get_value()
    {
        return $this->_value;
    }

    protected function _tips($key)
    {
        $Property = new ReflectionPropertyX($this, $key);
        return $Property->_tips ? "<span class='text-muted'>" . $Property->_tips . "</span>" : "";
    }

    /**
     * 获得这个字段的枚举数组 数组的定义：protected $_country = [];
     * 注意：数组的定义格式为：下标是储存值，数组值是显示出来的标签
     * @param $key
     * @throws \Exception
     * @return array
     */
    protected function _optional($key)
    {
        $values = self::${"{$key}s"};

        if (!$values) {
            throw new \Exception("!!未定义类静态变量public static \${$key}s");
        }
        return $values;
    }

    protected function _input_radio($key, $value, $data = null)
    {
        $data = $data ?: $this->_optional($key);
        foreach ($data as $option_value => $option_label) {
            $checked = (string)$value === (string)$option_value ? " checked='checked'" : "";
            echo "<div class=\"form-check radio\">";
            echo "<label class='form-check-label'>";
            echo "<input class='form-check-input' type=radio name=\"{$key}\" value=\"{$option_value}\" id=\"{$key}{$option_value}\"{$checked} />";
            echo $option_label;
            echo "</label>";
            echo "</div>";
        }
    }

    protected function _input_checkbox($key, $value, $data = null)
    {
        $data = $data ?: $this->_optional($key);
        foreach ($data as $option_value => $option_label) {
            if ($value && is_array($value))
                $checked = in_array($option_value, $value) ? " checked='checked'" : "";
            else
                $checked = "";

            echo "<div class=\"form-check checkbox\">";
            echo "<label class='form-check-label'>";
            echo "<input class='form-check-input' type=checkbox name=\"{$key}[]\" value=\"{$option_value}\"{$checked} />";
            echo $option_label;
            echo "</label>";
            echo "</div>";
        }
    }

    protected function _input_select($key, $value, $data = null)
    {
        $data = $data ?: $this->_optional($key);
        echo "<select name='{$key}' class='form-control'>";
        echo "<option value=''>--</option>";
        foreach ($data as $option_value => $option_label) {
            $selected = (string)$value === (string)$option_value ? " selected='selected'" : "";
            echo "<option value='{$option_value}'{$selected}>{$option_label}</option>";
        }
        echo "</select>";
    }

    protected function _input_textarea($key, $value)
    {
        echo "<textarea name='{$key}' class='form-control' rows='3'>{$value}</textarea>";
    }

    protected function _input_array($key, $value)
    {
        $this->_input_textarea($key, implode("\n", $value ?: []));
        echo "<div class=gray>多个请以换行隔开</div>";
    }

    protected function _input_bool($key, $value)
    {
        echo "<div class=\"form-check radio\"><label class='form-check-label'><input class=\"form-check-input\" type=radio name=\"{$key}\" value='0' " . ($value === false ? "checked='checked'" : "") . "> 否</label></div>";
        echo "<div class=\"form-check radio\"><label class='form-check-label'><input class=\"form-check-input\" type=radio name=\"{$key}\" value='1' " . ($value === true ? "checked='checked'" : "") . "> 是</label></div>";
    }

    protected function _input_string($key, $value)
    {
        $value = htmlentities($value);
        echo "<input class='form-control' type=text name=\"{$key}\" value=\"{$value}\">";
    }


    protected function _input_password($key, $value)
    {
        echo "<input class='form-control' type=password name=\"{$key}\" value=\"\">";
    }

    protected function _input_file($key, $value)
    {
        echo "<input class='form-control' type=file name=\"{$key}\" value=\"\">";
    }

    protected function _input_hidden($key, $value)
    {
        echo "<input type=hidden name=\"{$key}\" value=\"{$value}\">";
    }

    protected function _input_date($key, $value)
    {
        $value = Date::toString($value, "Y-m-d");
        echo "<input class='form-control' type=date name=\"{$key}\" value=\"{$value}\">";
    }

    protected function _input_datetime($key, $value)
    {
        $value = Date::toString($value, "Y-m-d\TH:i:s");
        echo "<input class='form-control' type=datetime-local name=\"{$key}\" value=\"{$value}\">";
    }
}
