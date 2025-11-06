<?php
/**
 * @author XJ.
 * @Date   2025/11/6
 */

namespace Fatbit\Utils\Enums;

use Fatbit\Enums\Annotations\EnumCase;
use Fatbit\Enums\Interfaces\EnumCaseInterface;
use Fatbit\Enums\Traits\EnumCaseGet;

enum DateGroupEnum implements EnumCaseInterface
{
    use EnumCaseGet;

    #[EnumCase(desc: '小时',)]
    case HOUR;

    #[EnumCase(desc: '天',)]
    case DAY;

    #[EnumCase(desc: '周',)]
    case WEEK;

    #[EnumCase(desc: '月',)]
    case MONTH;

    #[EnumCase(desc: '季度',)]
    case QUARTER;

    #[EnumCase(desc: '年',)]
    case YEAR;

    /**
     * 格式
     *
     * @author XJ.
     * @Date   2025/11/6
     * @return string|callable
     */
    public function format(): string|callable
    {
        return match ($this) {
            DateGroupEnum::HOUR    => 'Y-m-d H',
            DateGroupEnum::DAY     => 'Y-m-d',
            DateGroupEnum::WEEK    => 'Y年第W周',
            DateGroupEnum::MONTH   => 'Y-m',
            DateGroupEnum::QUARTER => fn(int $time) => date('Y', $time) . '第' . ceil(date('n', $time) / 3) . '季度',
            DateGroupEnum::YEAR    => 'Y',
        };

    }

}