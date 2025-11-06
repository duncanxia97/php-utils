<?php
/**
 * @author XJ.
 * @Date   2025/11/6
 */

namespace Fatbit\Utils\Helper;

class Str
{
    /**
     * 驼峰命名法
     *
     * @author XJ.
     * @Date   2025/11/6
     *
     * @param $value
     *
     * @return string
     */
    public static function camel($value)
    {
        return lcfirst(static::studly($value));
    }

    /**
     * 驼峰命名法
     *
     * @author XJ.
     * @Date   2025/11/6
     *
     * @param string $value
     * @param string $gap
     *
     * @return string
     */
    public static function studly(string $value, string $gap = ''): string
    {
        $value = ucwords(str_replace(['-', '_'], ' ', $value));

        return str_replace(' ', $gap, $value);
    }

    /**
     * 蛇形命名法
     *
     * @author XJ.
     * @Date   2025/11/6
     *
     * @param string $value
     * @param string $delimiter
     *
     * @return string
     */
    public static function snake(string $value, string $delimiter = '_'): string
    {
        if (!ctype_lower($value)) {
            $value = preg_replace('/\s+/u', '', ucwords($value));

            $value = static::lower(preg_replace('/(.)(?=[A-Z])/u', '$1' . $delimiter, $value));
        }

        return $value;
    }

    /**
     * 小写
     *
     * @author XJ.
     * @Date   2025/11/6
     *
     * @param $value
     *
     * @return string
     */
    public static function lower($value)
    {
        return mb_strtolower($value, 'UTF-8');
    }

    public static function upper(string $value): string
    {
        return mb_strtoupper($value, 'UTF-8');
    }

}