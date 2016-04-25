<?php
/**
 * Created by PhpStorm.
 * User: kingmax
 * Date: 16/2/17
 * Time: 下午2:31
 */

namespace rayful\Tool;


class Date
{
    /**
     * 将MongoDate格式或时间戳变成字符串
     * @param int|\MongoDate $date
     * @param string $pattern
     * @return bool|string
     */
    public static function toString($date, $pattern = "Y-m-d H:i")
    {
        if ($date && is_object($date) && ($date instanceof \MongoDate)) {
            return date($pattern, $date->sec);
        } elseif (is_int($date)) {
            return date($pattern, $date);
        }
    }

    /**
     * 以友好方式显示时间
     * @param int $timestamp
     * @return string
     */
    public static function friendly($timestamp)
    {
        $t = time() - $timestamp;
        $f = array(
            '31536000' => '年',
            '2592000' => '个月',
            '604800' => '周',
            '86400' => '天',
            '3600' => '小时',
            '60' => '分钟',
            '1' => '秒'
        );
        foreach ($f as $k => $v) {
            if (0 != $c = floor($t / (int)$k)) {
                return $c . $v . '前';
            }
        }
        return "刚刚";
    }

    /**
     * 返回两个时间戳之间的差值
     * @param int $timestamp
     * @return array
     */
    public static function distance($timestamp)
    {
        $second = $timestamp - time();
        if ($second > 0) {
            $day = floor($second / (3600 * 24));
            $second = $second % (3600 * 24);//除去整天之后剩余的时间
            $hour = floor($second / 3600);
            $second = $second % 3600;//除去整小时之后剩余的时间
            $minute = floor($second / 60);
            $second = $second % 60;//除去整分钟之后剩余的时间

            $distance = [
                'day' => $day,
                'hour' => $hour,
                'minute' => $minute,
            ];
            return $distance;
        }
    }

    /**
     * 返回两个时间戳之间的差值（文本显示形式）
     * @param int $timestamp
     * @return string
     */
    public static function distanceText($timestamp)
    {
        $distance = self::distance($timestamp);
        return $distance['day'] . "天" . $distance['hour'] . "小时" . $distance['minute'] . "分";
    }
}