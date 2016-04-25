<?php
/**
 * 对象的常用对属性的一些操作工具
 * Created by PhpStorm.
 * User: kingmax
 * Date: 16/4/20
 * Time: 下午10:14
 */

namespace rayful\Tool;


trait objectTool
{
    final public function set($data)
    {
        if ($data) {
            foreach ($data as $key => $value) {
                $this->{$key} = $value;
            }
        }
        return $this;
    }

    final public function toArray()
    {
        $publics = create_function('$obj', 'return get_object_vars($obj);');
        return $publics($this);
    }

    public function clear()
    {
        foreach ($this->toArray() as $key => $value) {
            unset($this->{$key});
        }
        return $this;
    }

    public function reset($data)
    {
        return $this->clear()->set($data);
    }
}