<?php
/**
 * @author XJ.
 * @Date   2025/11/6
 */

use Fatbit\Utils\Enums\DateGroupEnum;
use Fatbit\Utils\Enums\TimeDurationEnum;
use Fatbit\Utils\Helper\Str;

if (!function_exists('arr2json')) {
    /**
     * 序列化
     * Created by XJ.
     * Date: 2018/11/21.
     *
     * @param array|object $val
     * @param int          $flag = JSON_UNESCAPED_UNICODE
     *
     * @return null|string
     */
    function arr2json($val, int $flag = 256)
    {
        return is_array($val) || is_object($val) ? json_encode($val, $flag) : $val;
    }
}

if (!function_exists('json2arr')) {
    /**
     * 反序列化
     * Created by XJ.
     * Date: 2018/11/21.
     *
     * @param string|array|object $val
     *
     * @return null|array|object|string
     */
    function json2arr($val)
    {
        if (is_array($val) || $val === null || is_object($val) || is_numeric($val) || is_bool($val)) {
            return $val;
        }
        $tempVal = json_decode($val, true);

        return $tempVal ?? (is_string($val) ? $val : []);
    }
}

if (!function_exists('getSeconds')) {
    /**
     * Created by XJ.
     * Date: 2020/11/25.
     *
     * @param string $mark ['m', 'month', 'h']
     */
    function getSeconds(string $mark = 'm'): int
    {
        $mark = match ($mark) {
            default => $mark,
            'M'     => 'month',
        };

        $res = match (strtolower($mark)) {
            default       => strtotime($mark) - time(),
            'm', 'minute' => 60,
            'h', 'hour'   => 3600,    // 60*60
            'd', 'day'    => 86400,   // 3600*24
            'w', 'week'   => 604800,  // 86400*7
            'month'       => 2678400, // 86400*31
            'y', 'year'   => 31536000,// 86400*365
        };
        if ($res < 0) {
            return 0;
        }

        return $res;
    }
}
if (!function_exists('getClientIp')) {
    /**
     * 获取客户端IP
     *
     * @author XJ.
     * @Date   2024/12/4 星期三
     *
     * @param array $server
     *
     *
     * @return mixed|string
     */
    function getClientIp(array $server)
    {
        {
            if (isset($server['http_cf_connecting_ip'])) { // 支持Cloudflare
                $ip = $server['http_cf_connecting_ip'];
            } elseif (isset($server['REMOTE_ADDR']) === true) {
                $ip = $server['REMOTE_ADDR'];
                if (preg_match('/^(?:127|10)\.0\.0\.[12]?\d{1,2}$/', $ip)) {
                    if (isset($server['HTTP_X_REAL_IP'])) {
                        $ip = $server['HTTP_X_REAL_IP'];
                    } elseif (isset($server['http_x_forewarded_for'])) {
                        $ip = $server['http_x_forewarded_for'];
                    }
                }
            } else {
                $ip = '127.0.0.1';
            }
            if (in_array($ip, ['::1', '0.0.0.0', '本地主机'], true)) {
                $ip = '127.0.0.1';
            }
            $filter = filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
            if ($filter === false) {
                $ip = '127.0.0.1';
            }

            return $ip;
        }
    }
}
if (!function_exists('fill_zero')) {
    /**
     * 数值填充零
     * Created by XJ.
     * Date: 2019/9/6.
     *
     * @param     $val  string|int|float 数值
     * @param     $bit  int 位数
     * @param int $type 0|1 类型(0: 左填充，1：右填充)
     *
     * @return string
     */
    function fill_zero(&$val, int $bit, int $type = 0): string
    {
        $val = str_pad((string)$val, $bit, '0', $type);

        return $val;
    }
}

if (!function_exists('b642dec')) {
    /**
     * 64进制转10进制
     * Created by XJ.
     * Date: 2019/9/29.
     *
     * @param string $b64
     *
     * @return int
     */
    function b642dec(string $b64): int
    {
        $map = [
            '0' => 0,
            '1' => 1,
            '2' => 2,
            '3' => 3,
            '4' => 4,
            '5' => 5,
            '6' => 6,
            '7' => 7,
            '8' => 8,
            '9' => 9,
            'A' => 10,
            'B' => 11,
            'C' => 12,
            'D' => 13,
            'E' => 14,
            'F' => 15,
            'G' => 16,
            'H' => 17,
            'I' => 18,
            'J' => 19,
            'K' => 20,
            'L' => 21,
            'M' => 22,
            'N' => 23,
            'O' => 24,
            'P' => 25,
            'Q' => 26,
            'R' => 27,
            'S' => 28,
            'T' => 29,
            'U' => 30,
            'V' => 31,
            'W' => 32,
            'X' => 33,
            'Y' => 34,
            'Z' => 35,
            'a' => 36,
            'b' => 37,
            'c' => 38,
            'd' => 39,
            'e' => 40,
            'f' => 41,
            'g' => 42,
            'h' => 43,
            'i' => 44,
            'j' => 45,
            'k' => 46,
            'l' => 47,
            'm' => 48,
            'n' => 49,
            'o' => 50,
            'p' => 51,
            'q' => 52,
            'r' => 53,
            's' => 54,
            't' => 55,
            'u' => 56,
            'v' => 57,
            'w' => 58,
            'x' => 59,
            'y' => 60,
            'z' => 61,
            '_' => 62,
            '=' => 63,
        ];
        $dec = 0;
        $len = strlen($b64);
        for ($i = 0; $i < $len; ++$i) {
            $b = $map[$b64[$i]];
            if ($b === null) {
                return 0;
            }
            $j   = $len - $i - 1;
            $dec += ($j === 0 ? $b : (2 << (6 * $j - 1)) * $b);
        }

        return $dec;
    }
}

if (!function_exists('dec2b64')) {
    /**
     * 10进制转64进制
     * Created by XJ.
     * Date: 2019/9/29.
     *
     * @param int|string $dec
     */
    function dec2b64($dec): string
    {
        if ($dec < 0) {
            return '';
        }
        $map = [
            0  => '0',
            1  => '1',
            2  => '2',
            3  => '3',
            4  => '4',
            5  => '5',
            6  => '6',
            7  => '7',
            8  => '8',
            9  => '9',
            10 => 'A',
            11 => 'B',
            12 => 'C',
            13 => 'D',
            14 => 'E',
            15 => 'F',
            16 => 'G',
            17 => 'H',
            18 => 'I',
            19 => 'J',
            20 => 'K',
            21 => 'L',
            22 => 'M',
            23 => 'N',
            24 => 'O',
            25 => 'P',
            26 => 'Q',
            27 => 'R',
            28 => 'S',
            29 => 'T',
            30 => 'U',
            31 => 'V',
            32 => 'W',
            33 => 'X',
            34 => 'Y',
            35 => 'Z',
            36 => 'a',
            37 => 'b',
            38 => 'c',
            39 => 'd',
            40 => 'e',
            41 => 'f',
            42 => 'g',
            43 => 'h',
            44 => 'i',
            45 => 'j',
            46 => 'k',
            47 => 'l',
            48 => 'm',
            49 => 'n',
            50 => 'o',
            51 => 'p',
            52 => 'q',
            53 => 'r',
            54 => 's',
            55 => 't',
            56 => 'u',
            57 => 'v',
            58 => 'w',
            59 => 'x',
            60 => 'y',
            61 => 'z',
            62 => '_',
            63 => '=',
        ];
        $b64 = '';
        do {
            $b64 = $map[($dec % 64)] . $b64;
            $dec /= 64;
        } while ($dec >= 1);

        return $b64;
    }
}
if (!function_exists('to_number')) {
    /**
     * 转换数字格式
     * Created by XJ.
     * Date: 2020/11/19.
     *
     * @param string|float|int $var                 数值
     * @param int              $decimals            小数位
     * @param string           $thousands_separator 千分位
     *
     * @return string|null
     */
    function to_number($var, int $decimals = 4, string $thousands_separator = ''): ?string
    {
        if (!is_numeric($var)) {
            return null;
        }
        if (is_string($var)) {
            // 千分位替换
            $var = str_replace(',', '', $var);
        }

        return number_format((float)$var, $decimals, '.', $thousands_separator);
    }
}
if (!function_exists('quick_sort')) {
    /**
     * 快速排序
     * Created by XJ.
     * Date: 2019/2/21.
     *
     * @param bool|int $order
     */
    function quick_sort(array $arr, $order = 0): array
    {
        if (!isset($arr[1])) {
            return $arr;
        }
        $mid        = $arr[0];
        $leftArray  = [];
        $rightArray = [];
        foreach ($arr as $v) {
            if ($v > $mid) {
                $rightArray[] = $v;
            }
            if ($v < $mid) {
                $leftArray[] = $v;
            }
        }
        $leftArray   = quick_sort($leftArray, $order);
        $leftArray[] = $mid;
        $rightArray  = quick_sort($rightArray, $order);
        if ($order) {
            $sortArr = array_reverse([...$leftArray, ...$rightArray]);
        } else {
            $sortArr = [...$leftArray, ...$rightArray];
        }

        return $sortArr;
    }
}

if (!function_exists('multi_quick_sort')) {
    /**
     * 多维数组快速排序
     * Created by XJ.
     * Date: 2019/2/21.
     *
     * @param $arr   array 需要排序的数组
     * @param $key   string 根据那个字段进行排序
     * @param $order mixed 正序还是倒序
     */
    function multi_quick_sort(array $arr, string $key, bool $order = false): array
    {
        if (!isset($arr[1][$key])) {
            return $arr;
        }
        $mid = $arr[0];
        unset($arr[0]);
        $leftArray  = [];
        $rightArray = [];
        foreach ($arr as $v) {
            if ($v[$key] > $mid[$key]) {
                $rightArray[] = $v;
            } else {
                $leftArray[] = $v;
            }
        }
        $leftArray   = multi_quick_sort($leftArray, $key, $order);
        $leftArray[] = $mid;
        unset($mid);
        $rightArray = multi_quick_sort($rightArray, $key, $order);
        if ($order) {
            $sortArr = array_reverse([...$leftArray, ...$rightArray]);
        } else {
            $sortArr = [...$leftArray, ...$rightArray];
        }

        return $sortArr;
    }
}

if (!function_exists('img2base64')) {
    /**
     * 将图片转变为base64
     * Created by XJ.
     * Date: 2019/3/21.
     *
     * @param string $img
     *
     * @return false|string
     */
    function img2base64(string $img = '')
    {
        $file = file_get_contents($img);
        if ($file === false) {
            return false;
        }
        $imageInfo = getimagesize($img);

        return 'data:' . $imageInfo['mime'] . ';base64,' . chunk_split(base64_encode($file));
    }
}

if (!function_exists('hex2rgb')) {
    /**
     * hex转rgb
     * Created by XJ.
     * Date: 2019/9/27.
     *
     * @param $colour    string 颜色值
     * @param $is_string bool 是否返回字符串
     *
     * @return array|false|string
     */
    function hex2rgb(string $colour, bool $is_string = false)
    {
        if ($colour[0] === '#') {
            $colour = substr($colour, 1);
        }
        if (strlen($colour) === 6) {
            [$r, $g, $b] = [$colour[0] . $colour[1], $colour[2] . $colour[3], $colour[4] . $colour[5]];
        } elseif (strlen($colour) === 3) {
            [$r, $g, $b] = [$colour[0] . $colour[0], $colour[1] . $colour[1], $colour[2] . $colour[2]];
        } else {
            return $colour;
        }
        $r = hexdec($r);
        $g = hexdec($g);
        $b = hexdec($b);

        return $is_string ? 'rgb(' . $r . ',' . $g . ',' . $b . ')' : ['r' => $r, 'g' => $g, 'b' => $b];
    }
}

if (!function_exists('rgb2hex')) {
    /**
     * rgb转hex
     * Created by XJ.
     * Date: 2019/9/27.
     *
     * @param $rgb string rgb颜色值
     */
    function rgb2hex(string $rgb): string
    {
        if ($rgb[0] === '#') {
            return $rgb;
        }
        $regexp = '/^rgb\\(([0-9]{0,3}),\\s*([0-9]{0,3}),\\s*([0-9]{0,3})\\)/';
        preg_match($regexp, $rgb, $match);
        array_shift($match);
        $hexColor = '#';
        $hex      = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F'];
        for ($i = 0; $i < 3; ++$i) {
            $c     = $match[$i];
            $hexAr = [];
            while ($c > 16) {
                $r       = $c % 16;
                $c       = ($c / 16) >> 0;
                $hexAr[] = $hex[$r];
            }
            $hexAr[] = $hex[$c];
            $ret     = array_reverse($hexAr);
            $item    = implode('', $ret);
            fill_zero($item, 2, STR_PAD_LEFT);
            $hexColor .= $item;
        }

        return $hexColor;
    }
}

if (!function_exists('array_sequence')) {
    /**
     * 用于对二维数组某个key值排序
     * Created by XJ.
     * Date: 2020/11/19.
     *
     * @param array  $array 二维数组
     * @param string $field key值
     * @param int    $sort  排序方式，默认降序
     */
    function array_sequence(array $array, string $field, int $sort = SORT_DESC): array
    {
        $arrSort = [];
        foreach ($array as $uniqid => $row) {
            foreach ($row as $key => $value) {
                $arrSort[$key][$uniqid] = $value;
            }
        }
        array_multisort($arrSort[$field], $sort, $array);

        return $array;
    }
}

if (!function_exists('array_excess')) {
    /**
     * 去除数组多余数据
     * Created by XJ.
     * Date: 2020/11/25.
     */
    function array_excess(array $arr): array
    {
        return array_filter(array_unique($arr));
    }
}

if (!function_exists('arr2tree')) {
    /**
     * 数组转树形结构
     * Created by XJ.
     * Date: 2021-05-31
     *
     * @param array      $list
     * @param int|string $pid
     * @param string     $pidField
     * @param string     $pkField
     * @param int|null   $maxLevel
     * @param string     $childrenName
     * @param int        $level
     *
     * @return array|null
     */
    function arr2tree(
        array     $list,
                  $pid = 0,
        string    $pidField = 'pid',
        string    $pkField = 'id',
        ?int      $maxLevel = null,
        string    $childrenName = 'children',
        int       $level = 1,
        ?callable $toVal = null,
    ): ?array {
        return __arr2tree($list, $pid, $pidField, $pkField, $maxLevel, $childrenName, $level, $toVal);
    }

    /**
     * 遍历优化(优化后是之前得一倍)
     * Created by XJ.
     * Date: 2021-05-31
     *
     * @param array      $list
     * @param int|string $pid
     * @param string     $pidField
     * @param string     $pkField
     * @param int|null   $maxLevel
     * @param string     $childrenName
     * @param int        $level
     *
     * @return array|null
     */
    function __arr2tree(
        array     &$list,
                  $pid = 0,
        string    $pidField = 'pid',
        string    $pkField = 'id',
        ?int      $maxLevel = null,
        string    $childrenName = 'children',
        int       $level = 1,
        ?callable $toVal = null,
    ): ?array {
        if ($maxLevel !== null && $level > $maxLevel) {
            return [];
        }
        $data = [];
        foreach ($list as $k => $val) {
            if (!isset($val[$pidField], $val[$pkField])) {
                continue;
            }
            if ($val[$pidField] === $pid) {
                $temp                                     = $val + [
                        $childrenName . 'Level' => $level,
                        $childrenName           => __arr2tree(
                            $list,
                            $val[$pkField],
                            $pidField,
                            $pkField,
                            $maxLevel,
                            $childrenName,
                            $level + 1,
                        ),
                    ];
                $temp[Str::camel('has_' . $childrenName)] = count($temp[$childrenName]) > 0;
                if (is_callable($toVal)) {
                    $temp = $toVal($temp) + $temp;
                }
                $data[] = $temp;
                unset($list[$k]);
            }
        }

        return $data;
    }
}

if (!function_exists('tree2arr')) {
    /**
     * 树形结构转数组结构
     * Created by XJ.
     * Date: 2021-06-17
     *
     * @param array  $tree
     * @param string $childrenName
     * @param int    $level
     * @param array  $arr
     *
     * @return array|null
     */
    function tree2arr(array $tree, string $childrenName = 'children', int $level = 1, array &$arr = []): ?array
    {
        foreach ($tree as $val) {
            $children = $val[$childrenName] ?? [];
            unset($val[$childrenName]);
            $arr[] = array_merge($val, [$childrenName . 'Level' => $level]);
            if ($children) {
                tree2arr($children, $childrenName, $level + 1, $arr);
            }
        }

        return $arr;
    }
}

if (!function_exists('getParents')) {
    /**
     * 获取父级
     * Created by XJ.
     * Date: 2021-06-21
     *
     * @param          $parentId
     * @param array    $list       数组
     * @param string   $pidField   pid字段
     * @param string   $pkField    主键字段
     * @param int|null $maxLevel   最大深度
     * @param bool     $isTree     是否返回树形结构
     * @param string   $parentName 父级字段名称
     * @param int      $level      当前层级
     * @param array    $data       数据
     *
     * @return array|null
     */
    function getParents(
        $parentId,
        array $list,
        string $pidField = 'pid',
        string $pkField = 'id',
        ?int $maxLevel = null,
        bool $isTree = true,
        string $parentName = 'parent',
        int $level = 1,
        array &$data = [],
    ): ?array {
        if ($maxLevel && $maxLevel < $level) {
            return [];
        }
        $temp = [];
        foreach ($list as &$val) {
            if ($parentId === $val[$pkField]) {
                $temp_  = array_merge($val, [$parentName . 'Level' => $level]);
                $data[] = $temp_;
                unset($val);
                $res = getParents(
                    $temp_[$pidField],
                    $list,
                    $pidField,
                    $pkField,
                    $maxLevel,
                    $isTree,
                    $parentName,
                    $level + 1,
                    $data,
                );
                if ($isTree) {
                    $temp_[$parentName] = $res;
                    $temp[]             = $temp_;
                }
            }
        }

        return $isTree ? $temp : $data;
    }
}

if (!function_exists('remove_empty_str')) {
    /**
     * 去除空字符串
     * Created by XJ.
     * Date: 2021-07-07
     *
     * @param string          $str
     * @param string[]|string $remove
     *
     * @return string
     */
    function remove_empty_str(string $str, $remove = [' ', '\n', '\r', '\t']): string
    {
        return str_replace($remove, '', $str);
    }
}

if (!function_exists('dateGroup')) {
    /**
     * 日期分组
     *
     * @author XJ.
     * Date: 2022/9/16 0016
     *
     * @param int|string           $startTime   开始时间
     * @param int|string           $endTime     结束时间
     * @param DateGroupEnum        $type        分组类型
     * @param string|callable|null $groupFormat 分组格式
     * @param int                  $step        步进
     *
     * @return array
     */
    function dateGroup(int|string $startTime, int|string $endTime, DateGroupEnum $type, null|string|callable $groupFormat = null, int $step = 1)
    {
        if (is_string($startTime)) {
            // 转为时间戳
            $startTime = strtotime($startTime);
        }
        if (is_string($endTime)) {
            // 转为时间戳
            $endTime = strtotime($endTime);
        }
        if (is_null($groupFormat)) {
            // 默认格式
            $groupFormat = $type->format();
        }
        $res = [];
        $i   = 0;
        switch ($type) {
            default:
            case DateGroupEnum::DAY:
                $tempTime = strtotime(date('Y-m-d', $startTime));
                $endTime  = strtotime(date('Y-m-d', strtotime("- {$step} day +1 day", $endTime)));
                do {
                    $tempTime = strtotime('+' . $i . ' day', $startTime);
                    $res[]    = [
                        'name'  => time2Format($tempTime, $groupFormat),
                        'start' => strtotime(date('Y-m-d 00:00:00', $tempTime)),
                        'end'   => strtotime(date('Y-m-d 23:59:59', $tempTime)),
                    ];
                    $i        += $step;
                } while ($tempTime < $endTime);
                break;
            case DateGroupEnum::HOUR:
                $tempTime = strtotime(date('Y-m-d H' . ':00:00', $startTime));
                $endTime  = strtotime(date('Y-m-d H' . ':00:00', strtotime("- {$step} hour +1 hour", $endTime)));
                do {
                    $tempTime = strtotime('+' . $i . ' hour', $startTime);
                    $res[]    = [
                        'name'  => time2Format($tempTime, $groupFormat),
                        'start' => strtotime(date('Y-m-d H:00:00', $tempTime)),
                        'end'   => strtotime(date('Y-m-d H:59:59', $tempTime)),
                    ];
                    $i        += $step;
                } while ($tempTime < $endTime);
                break;
            case DateGroupEnum::WEEK:
                $tempTime = strtotime('this week Monday', $startTime);
                $endTime  = strtotime('this week Sunday', strtotime("-{$step} week +1 week", $endTime));
                do {
                    $week     = strtotime('this week Monday +' . $i . ' week', $startTime);
                    $tempTime = strtotime('this week Sunday 23:59:59', $week);
                    $res[]    = [
                        'name'  => time2Format($week, $groupFormat),
                        'start' => strtotime('this week Monday', $week),
                        'end'   => $tempTime
                    ];
                    $i        += $step;
                } while ($tempTime < $endTime);
                break;
            case DateGroupEnum::MONTH:
                $tempTime = strtotime(date('Y-m-01', $startTime));
                $endTime  = strtotime(date('Y-m-t', strtotime("- {$step} month +1 month", $endTime)));
                do {
                    $month    = strtotime('first day of +' . $i . ' month', $startTime);
                    $tempTime = strtotime(date('Y-m-t 23:59:59', $month));
                    $res[]    = [
                        'name'  => time2Format($month, $groupFormat),
                        'start' => strtotime(date('Y-m-01', $month)),
                        'end'   => $tempTime,
                    ];
                    $i        += $step;
                } while ($tempTime < $endTime);
                break;
            case DateGroupEnum::QUARTER:
                $tempTime    = strtotime(date('Y-m', $startTime));
                $quarterStep = $step * 3;
                $endTime     = date('Y-m', strtotime("- {$quarterStep} month +3 month", $endTime));
                do {
                    $quarter  = strtotime('first day of +' . $i . ' month', $startTime);
                    $q        = (int)ceil(date('n', $quarter) / 3);
                    $tempTime = strtotime(date('Y-m-t H:i:s', mktime(23, 59, 59, $q * 3, 1, (int)date('Y', $quarter))));
                    $res[]    = [
                        'name'  => time2Format($quarter, $groupFormat),
                        'start' => strtotime(date('Y-m-01', mktime(0, 0, 0, $q * 3 - 3 + 1, 1, (int)date('Y', $quarter)))),
                        'end'   => $tempTime,
                    ];
                    $i        += 3 + $step;
                } while ($tempTime < $endTime);
                break;
            case DateGroupEnum::YEAR:
                $tempTime = strtotime(date('Y-01-01', $startTime));
                $endTime  = strtotime(date('Y-12-31 23:59:59', strtotime("- {$step} year +1 year", $endTime)));
                do {
                    $year     = strtotime('+' . $i . ' year', $startTime);
                    $tempTime = strtotime(date('Y-12-31 23:59:59', $year));
                    $res[]    = [
                        'name'  => time2Format($year, $groupFormat),
                        'start' => strtotime(date('Y-01-01', $year)),
                        'end'   => strtotime(date('Y-12-31 23:59:59', $year)),
                    ];
                    $i        += $step;
                } while ($tempTime < $endTime);
                break;
        }

        return $res;
    }
}

if (!function_exists('time2Format')) {
    /**
     * 时间格式化
     *
     * @author XJ.
     * Date: 2022/9/16 0016
     *
     * @param int             $time
     * @param string|callable $format
     *
     * @return string
     */
    function time2Format(int $time, string|callable $format): string
    {
        return is_callable($format) ? $format($time) : date($format, $time);
    }
}

if (!function_exists('encryptData')) {
    /**
     * 加密数据
     *
     * @author XJ.
     * @Date   2025/9/12
     *
     * @param string      $data
     * @param string|null $privateKey
     *
     * @return string
     * @throws \Random\RandomException
     */
    function encryptData(string $data, ?string $privateKey = null, string $cipherAlgo = 'AES-256-CBC')
    {
        $iv_length     = openssl_cipher_iv_length($cipherAlgo);
        $options       = 0;
        $encryption_iv = random_bytes($iv_length);
        $encryption    = openssl_encrypt($data, $cipherAlgo, $privateKey, $options, $encryption_iv);

        return base64_encode($encryption_iv . $encryption);
    }
}

if (!function_exists('decryptData')) {
    /**
     * 解密数据
     *
     * @author XJ.
     * @Date   2025/9/12
     *
     * @param string      $encryptedData
     * @param string|null $privateKey
     *
     * @return false|string
     */
    function decryptData(string $encryptedData, ?string $privateKey = null, $cipherAlgo = 'AES-256-CBC')
    {
        $iv_length     = openssl_cipher_iv_length($cipherAlgo);
        $encryptedData = base64_decode($encryptedData);
        $encryption_iv = substr($encryptedData, 0, $iv_length);
        $encryptedData = substr($encryptedData, $iv_length);
        $options       = 0;

        return openssl_decrypt($encryptedData, $cipherAlgo, $privateKey, $options, $encryption_iv);
    }
}

if (!function_exists('tryCatch')) {
    /**
     * 尝试捕获异常
     *
     * @author XJ.
     * @Date   2025/11/7
     *
     * @param callable      $try
     * @param               $default
     * @param callable|null $catch
     * @param callable|null $finally
     *
     * @return mixed
     */
    function tryCatch(callable $try, $default = null, ?callable $catch = null, ?callable $finally = null): mixed
    {
        try {
            return $try();
        } catch (\Throwable $e) {
            if (!is_null($catch)) {
                $catch($e);
            }

            return $default;
        } finally {
            if (!is_null($finally)) {
                $finally();
            }
        }
    }
}

if (!function_exists('formatDuration')) {
    /**
     * 格式化时长为可读格式
     *
     * @author XJ.
     * @Date   2025/12/1
     *
     * @param float            $duration  时长数值
     * @param int              $precision 小数位数
     * @param TimeDurationEnum $basic     基础时间单位
     *
     * @return string
     */
    function formatDuration(float $duration, int $precision = 6, TimeDurationEnum $basic = TimeDurationEnum::MILLISECOND): string
    {
        return $basic->formatDuration($duration, $precision);
    }
}