<?php
/**
 * @author XJ.
 * @Date   2025/11/7
 */

namespace Fatbit\Utils\Helper;

class Arr
{

    /**
     * 获取数组的第一个元素
     *
     * @author XJ.
     * @Date   2025/11/7
     *
     * @param array $arr
     *
     * @return false|mixed
     */
    public static function first(array $arr)
    {
        return reset($arr);
    }

    /**
     * 获取数组的最后一个元素
     *
     * @author XJ.
     * @Date   2025/11/7
     *
     * @param array $arr
     *
     * @return false|mixed
     */
    public static function last(array $arr)
    {
        return end($arr);
    }

    /**
     * 获取数组的指定键值
     *
     * @author XJ.
     * @Date   2025/11/7
     *
     * @param array            $array
     * @param array|int|string $keys
     *
     * @return array
     */
    public static function only(array $array, array|int|string $keys): array
    {
        return array_intersect_key($array, array_flip((array)$keys));
    }

}