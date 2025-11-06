<?php
/**
 * @author XJ.
 * Date: 2023/7/3 0003
 */

namespace Fatbit\Utils\Params;

use Fatbit\Utils\Params\Traits\FillParams;
use Fatbit\Utils\Params\Traits\ObjectArrayAble;

class AbstractParam implements \ArrayAccess, \Countable, \IteratorAggregate
{
    use FillParams, ObjectArrayAble;

}