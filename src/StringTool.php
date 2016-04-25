<?php
/**
 * Created by PhpStorm.
 * User: kingmax
 * Date: 16/2/5
 * Time: 下午8:33
 */

namespace rayful\Tool;


class StringTool
{
    public static function toMongoId($string) {
        if($string && is_string($string)){
            return new \MongoId($string);
        }else{
            return $string;
        }
    }

    public static function toArray($string){
        $string = str_replace(array("\r\n", "\r", "\r\n"), "\n", $string); //PHP_EOL not work in Mac
        return explode("\n",$string);
    }

    public static function toMongoIds($string) {
        return array_map(["self","toMongoId"],self::toArray($string));
    }

    public static function shorten($string, $max_len) {
        if (mb_strlen($string, "utf-8") > $max_len) {
            $string = mb_substr($string, 0, $max_len, 'utf-8') . "..";
        }
        return $string;
    }

    /**
     * 一个字符串的常用方法，用于检查某个字符串里面是否存在一些特定的字符串
     * @param string $haystack
     * @param array $needles
     * @return bool
     */
    public static function isNeedlesExists($haystack, $needles) {
        foreach($needles as $needle) {
            if(stripos($haystack, $needle)!==false){
                return true;
            }
        }
        return false;
    }

    public static function slug($text)
    {
        return strtolower(trim(preg_replace('~[^0-9a-z]+~i', '-', html_entity_decode(preg_replace('~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($text, ENT_QUOTES, 'UTF-8')), ENT_QUOTES, 'UTF-8')), '-'));
    }

    public static function hasCn($text)
    {
        return preg_match("/\p{Han}+/u", $text);
    }

    public static function removeSpecialChar($text)
    {
        return preg_replace('~[^0-9a-z]+~i', '', $text);
    }

    /**
     * 是否URL格式
     * @param $string
     * @return boolean
     */
    public static function isUrl($string)
    {
        return boolval(filter_var($string, FILTER_VALIDATE_URL));
    }

    /**
     * 是否Email
     * @param $string
     * @return bool
     */
    public static function isEmail($string)
    {
        return boolval(filter_var($string, FILTER_VALIDATE_EMAIL));
    }

    /**
     * 是否移动电话（中国大陆)
     * @param $string
     * @return bool
     */
    public static function isMobile($string)
    {
        $mobile_range = ["options" =>
            ["min_range" => 10000000000, "max_range" => 20000000000]];
        return boolval(filter_var($string, FILTER_VALIDATE_INT, $mobile_range));
    }
}