<?php
/**
 * @author XJ.
 * @Date   2025/10/23 星期四
 */

namespace Fatbit\Utils\Params\Attributes;

use Attribute;

/**
 * 参数数组项类型
 *
 * @author XJ.
 * @Date   2025/10/23 星期四
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class ParamArrayItemType
{
    public function __construct(readonly public string $class)
    {
    }

}