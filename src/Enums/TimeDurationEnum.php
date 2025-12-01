<?php
/**
 * @author XJ.
 * @Date   2025/12/1
 */

namespace Fatbit\Utils\Enums;

use Fatbit\Enums\Annotations\EnumCase;
use Fatbit\Enums\Interfaces\EnumCaseInterface;
use Fatbit\Enums\Traits\EnumCaseGet;

/**
 * @author XJ.
 * @Date   2025/12/1
 * @method format() string 返回格式化后的时间单位字符串
 */
enum TimeDurationEnum: int implements EnumCaseInterface
{
    use EnumCaseGet;

    #[EnumCase('毫秒', ext: ['format' => 'ms'])]
    case MILLISECOND = 1;

    #[EnumCase('秒', ext: ['format' => 's'])]
    case SECOND = 1000;

    #[EnumCase('分钟', ext: ['format' => 'min'])]
    case MINUTE = 60000;

    // 60 * 60 * 1000
    #[EnumCase('小时', ext: ['format' => 'h'])]
    case HOUR = 3600000;

    // 24 * 60 * 60 * 1000
    #[EnumCase('天', ext: ['format' => 'd'])]
    case DAY = 86400000;

    // 24 * 60 * 60 * 1000 * 7
    #[EnumCase('周', ext: ['format' => 'w'])]
    case WEEK = 604800000;

    // 24 * 60 * 60 * 1000 * 30
    #[EnumCase('月', ext: ['format' => 'm'])]
    case MONTH = 2592000000;

    // 24 * 60 * 60 * 1000 * 365
    #[EnumCase('年', ext: ['format' => 'y'])]
    case YEAR = 31536000000;

    /**
     * 转换为秒
     *
     * @author XJ.
     * @Date   2025/12/1
     * @return float
     */
    public function toSecond(): float
    {
        return $this->value / self::SECOND->value;
    }

    /**
     * 获取所有时间单位枚举
     *
     * @author XJ.
     * @Date   2025/12/1
     *
     * @param bool $reverse
     *
     * @return self[]
     */
    public static function all(bool $reverse = false): array
    {
        $arr = [
            self::YEAR,
            self::MONTH,
            self::WEEK,
            self::DAY,
            self::HOUR,
            self::MINUTE,
            self::SECOND,
            self::MILLISECOND,
        ];
        if ($reverse) {
            return array_reverse($arr);

        }

        return $arr;
    }
}
