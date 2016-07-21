<?php
/**
 * Created by PhpStorm.
 * User: kingmax
 * Date: 16/7/13
 * Time: 下午10:09
 */

namespace rayful\Tool;


trait logPlugin
{

    /**
     * 帐户日志
     * @var array|Log[]
     * @name 日志
     * @ignore editor
     */
    public $logs;

    /**
     * 最大的日志条数
     * @return int
     */
    abstract function maxLogsNum();

    /**
     * 插入日志
     * @param string $content 日志内容
     * @param string $username 操作人姓名
     */
    public function addLog($content, $username = '')
    {
        $Log = new Log();
        $Log->setContent($content);
        $Log->setUsername($username);
        $Log->initDate();
        $this->logs[] = $Log->toArray();
        if($this->maxLogsNum() && count($this->logs)>$this->maxLogsNum()){
            array_shift($this->logs);   //移除第1条
        }
    }

    /**
     * 插入日志并保存
     * @param string $content 日志内容
     * @param string $username 操作人姓名
     */
    public function saveLog($content, $username = '')
    {
        $this->addLog($content, $username);
        $this->save();
    }

    /**
     * 根据内容查找日志
     * @param string $text
     * @return Log|null
     */
    public function findLog($text)
    {
        foreach ($this->logs() as $Log) {
            if ($Log->content() == $text) {
                return $Log;
            }
        }
    }

    /**
     * 遍历出所有日志数据
     * @param int $sort 1为正序,-1为倒序
     * @return Log[]
     */
    public function logs($sort = 1)
    {
        $logDataSet = (array)$this->logs;
        if ($sort === -1) {
            $logDataSet = array_reverse($logDataSet);
        }
        foreach ($logDataSet as $logData) {
            $Log = new Log($logData);
            yield $Log;
        }
    }

    abstract public function save();
}
