<?php
/**
 * 用于读取类里面的属性值,可以把在PHPDoc里面声明的任意@ 属性变成当前对象的真正属性
 * Created by PhpStorm.
 * User: kingmax
 * Date: 16/2/5
 * Time: 下午8:28
 */

namespace rayful\Tool;


class ReflectionPropertyX extends \ReflectionProperty
{
    /**
     * 通常在PHPDoc里面值这个类声明的这个属性的类型
     * @var string
     */
    public $_var;

    /**
     * __construct function.
     * @param mixed $class object or a string(class name)
     * @param string $name
     */
    public function __construct($class, $name){
        parent::__construct($class, $name);
        $this->getAllTags();
    }

    /**
     * getAllTags function.
     * @return void
     */
    private function getAllTags(){
        if($comment = $this->getDocComment()){
            if(preg_match_all("/\@(\w+)(.*)/", $comment,$m)){
                foreach($m[0] as $i=>$result){
                    $tag = $m[1][$i];
                    $tagValue = trim($m[2][$i]);
                    $this->{"_{$tag}"} = $tagValue;
                }
            }
        }
    }
}