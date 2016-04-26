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
     * Model类在PHPDoc里面对这个属性的类型的声明
     * 使用场景:editor,shower,validate中的autoConvert()
     * @var string
     * @example string int bool float array string[] MongoDate MongoId ...
     */
    public $_var;

    /**
     * Model类在PHPDoc里面对这个属性的标签的声明
     * 使用场景:editor,shower时必选
     * @var string
     * @example 姓名/性别/标题
     */
    public $_name;

    /**
     * editor中使用,声明这个属性的输入框类型
     * 使用场景:editor 可选 如果不声明类型，程序还将能自动根据声明的var类型判断
     * @var string
     * @example select/checkbox/radio/textarea
     */
    public $_input;

    /**
     * editor中使用，声明这个属性是否在某些编辑场景中被忽略
     * 使用场景:editor 可选 默认每个字段都会输出到编辑器中,除非声明ignore
     * @var string
     * @example edit/show
     */
    public $_ignore;

    /**
     * editor中使用,声明这个属性在输入框后面的提示
     * 使用场景:editor 可选 如果需要在输入框后面增加提示，请声明这个类型
     * @var string
     */
    public $_tips;


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