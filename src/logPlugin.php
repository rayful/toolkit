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
     * @var array|StdLog[]
     */
    public $logs;

    /**
     * 插入日志
     * @param string $content 日志内容
     * @param string $username 操作人姓名
     * @param int|null $limit 最大日志数
     */
    public function addLog($content, $username = '', $limit = null)
    {
        $Log = new Log();
        $Log->setContent($content);
        $Log->setUsername($username);
        $this->logs[] = $Log->toArray();
        if($limit && count($this->logs)>$limit){
            array_shift($this->logs);   //移除第1条
        }
    }

    /**
     * 插入日志并保存
     * @param string $content 日志内容
     * @param string $username 操作人姓名
     * @param int|null $limit 最大日志数
     */
    public function saveLog($content, $username = '', $limit = null)
    {
        $this->addLog($content, $username, $limit);
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