<?php
/**
 * Created by PhpStorm.
 * User: kingmax
 * Date: 16/7/13
 * Time: 下午9:58
 */

namespace rayful\Tool;

/**
 * 标准日志类,一般以数组形式储存在其它数据表内
 * @example
 * 创建日志
 * $Log = new Log("扣减余额100");
 * $this->logs[] = $Log->toArray();
 *
 * 循环迭代日志
 * function logs(){
 *  foreach((array)$this->logs as $logData){
 *      yield new Log($logData);
 *  }
 * }
 */
class Log
{
    use objectTool;

    /**
     * 日志时间
     * @var \MongoDate
     */
    private $date;

    /**
     * 日志操作用户名
     * @var string
     */
    private $username;

    /**
     * 日志内容
     * @var string
     */
    private $content;

    public function __construct($data = null)
    {
        if(is_array($data)){
            $this->set($data);
        }
    }

    /**
     * @param string $username
     * @return $this
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @param string $content
     * @return $this
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    
    public function initDate()
    {
        $this->date = new \MongoDate();
    }

    public function date()
    {
        return $this->date;
    }
    
    public function username()
    {
        return $this->username;
    }

    public function content()
    {
        return $this->content;
    }
    
    public function dateText()
    {
        return Date::toString($this->date);
    }

    public function contentHtml()
    {
        return nl2br($this->content);
    }
}