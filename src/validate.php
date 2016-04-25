<?php
/**
 * Created by PhpStorm.
 * User: kingmax
 * Date: 16/2/5
 * Time: 下午8:27
 */

namespace rayful\Tool;


trait validate
{
    use objectTool;

    /**
     * 根据在PHPDoc里面声明的属性（字段）类型，作出自动类型转换。一般用在保存之前，或写在子类的validate方法里面。
     * 注意是trait，不能重写覆盖。
     * @return void
     */
    public function autoConvert()
    {
        $vars = $this->toArray();
        foreach ($vars as $name => $value) {
            if (is_null($value)) continue;

            //通过ReflectionProperty类获取字段的类型
            $PropertyX = new ReflectionPropertyX($this, $name);
            $type = $PropertyX->_var;
            $type = strtolower($type);
            $type = preg_replace("/^\\\/", "", $type);

            //开始自动转换
            if (($name == "_id" && is_string($value)) || ($type == "mongoid" && is_string($value))) {
                if ($value)
                    $this->{$name} = new \MongoId($value);
                else
                    $this->{$name} = null;
            } elseif ($type == "mongoid[]") {
                if (!$value) $this->{$name} = null;
                elseif (is_string($value)) $this->{$name} = StringTool::toMongoIds($value);
                elseif (is_array($value)) $this->{$name} = array_map(["\\rayful\\Tool\\StringTool", "toMongoId"], $value);

            } elseif ($type == "bool" || $type == "boolean") {
                if ($value === "false") {
                    $value = false;
                }
                $this->{$name} = boolval($value);

            } elseif ($type == "string") {
                $this->{$name} = strval($value);

            } elseif ($type == "float") {
                $this->{$name} = floatval($value);

            } elseif ($type == "int" || $type == "integer") {
                $this->{$name} = intval($value);

            } elseif ($type == "mongodate") {    //主要针对淘宝传过来的时间是字符串类型
                if (is_int($value))
                    $this->{$name} = new \MongoDate($value);
                elseif (is_object($value) && $value instanceof \MongoDate)
                    $this->{$name} = $value;
                elseif ($value)
                    $this->{$name} = new \MongoDate(strtotime($value));

            } elseif (($type == "array" || strpos($type, "[]") !== false) && is_string($value)) {        //主要针对使用textarea post过来是字符串类型的字段
                $value = trim($value);
                if ($value) {
                    $value = StringTool::toArray($value);
                    $this->{$name} = $value;
                } else {
                    $this->{$name} = null;
                }
            }
        }
    }

    /**
     * 自动清除当前对象空白的属性（字段）
     * @return void
     */
    protected function filterNull()
    {
        $vars = $this->toArray();
        foreach ($vars as $name => $value) {
            if (is_null($value)) {
                unset($this->{$name});
            }
        }
    }

    /**
     * 自动将当前对象空的属性 ""/false/null 清除，除了："0"
     */
    protected function filterEmpty()
    {
        $vars = $this->toArray();
        foreach ($vars as $name => $value) {
            if (!strlen($value)) {
                unset($this->{$name});
            }
        }
    }

    /**
     * 自动将在类里面没有声明的属性去掉
     * @param   string $class_name
     */
    protected function filterUndefined($class_name)
    {
        $vars = $this->toArray();
        foreach ($vars as $name => $value) {
            if ($name != "_id" && !self::isDefined($class_name, $name)) {
                unset($this->{$name});
            }
        }
    }

    /**
     * 检查特定类是否具有指定属性
     * @param string $class_name
     * @param string $property_name
     * @return bool
     */
    private static function isDefined($class_name, $property_name)
    {
        return (new \ReflectionClass($class_name))->hasProperty($property_name);
    }

    /**
     * 自动去掉字符的空白
     */
    protected function autoTrim()
    {
        $vars = $this->toArray();
        $vars = array_map("Filter::trim", $vars);
        $this->set($vars);
    }

    protected function autoEntityDecode()
    {
        $vars = $this->toArray();
        $vars = array_map("html_entity_decode", $vars);
        $this->set($vars);
    }
}