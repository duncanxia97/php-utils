<?php
/**
 * @author XJ.
 * Date: 2023/7/3 0003
 */

namespace Fatbit\Utils\Params\Traits;

use Fatbit\Utils\Helper\Str;
use Fatbit\Utils\Params\AbstractParam;
use Fatbit\Utils\Params\Attributes\ParamArrayItemType;

trait FillParams
{
    use ToArrayJson;

    public function __construct(?array $data = null)
    {
        if ($data) {
            $vars = get_class_vars(static::class);
            foreach ($vars as $key => $val) {
                if (isset($data[$key])) {
                    // 原始key 名
                    $this->assignmentProperty($key, $data[$key]);
                } elseif (isset($data[$camelKey = Str::snake($key)])) {
                    // 兼容驼峰
                    $this->assignmentProperty($key, $data[$camelKey]);
                }
            }
        }
        if (get_parent_class(self::class) !== false && method_exists(parent::class, '__construct')) {
            parent::__construct($data);
        }
    }

    /**
     * 属性赋值
     *
     * @author XJ.
     * @Date   2025/10/23 星期四
     *
     * @param string $propertyName  属性名称
     * @param mixed  $propertyValue 属性值
     *
     * @throws \ReflectionException
     */
    private function assignmentProperty(string $propertyName, mixed $propertyValue)
    {
        // 如果是对象或者null不需要转换
        if (!is_object($propertyValue) && !is_null($propertyValue)) {
            // 获取属性类型
            $property     = new \ReflectionProperty($this, $propertyName);
            $propertyType = $property->getType();
            // 非未定义类型才转换
            if (!is_null($propertyType)) {
                $propertyValue = $this->convertProperty($propertyType, $property, $propertyValue);
            }
        }
        $this->{$propertyName} = $propertyValue;
    }

    /**
     * 属性值转换
     *
     * @author XJ.
     * @Date   2025/10/23 星期四
     *
     * @param \ReflectionType $propertyType  属性类型
     * @param mixed           $propertyValue 属性值
     *
     * @return mixed|\BackedEnum|AbstractParam
     */
    private function convertProperty(\ReflectionType $propertyType, \ReflectionProperty $property, mixed $propertyValue): mixed
    {
        // 如果属性单类型，则进行转换
        if ($propertyType instanceof \ReflectionNamedType) {
            // 非内置类型
            $typeName = $propertyType->getName();
            if (!$propertyType->isBuiltin()) {
                return $this->convertPropertyValue($typeName, $propertyValue);
            }

            // 数组类型兼容
            if ($typeName === 'array' && is_array($propertyValue)) {
                return $this->convertPropertyArrayValue($property, $propertyValue);
            }
        } elseif ($propertyType instanceof \ReflectionUnionType) {
            // 联合类型兼容
            foreach ($propertyType->getTypes() as $type) {
                $newValue = $this->convertProperty($type, $property, $propertyValue);
                if ($newValue !== $propertyValue) {
                    return $newValue;
                }
            }
        }


        return $propertyValue;
    }

    /**
     * 数组类型转换
     *
     * @author XJ.
     * @Date   2025/10/23 星期四
     *
     * @param \ReflectionProperty $property
     * @param array               $propertyValue
     *
     * @return array
     */
    private function convertPropertyArrayValue(\ReflectionProperty $property, array $propertyValue)
    {
        $attr = $property->getAttributes(ParamArrayItemType::class)[0] ?? null;
        if (is_null($attr)) {
            return $propertyValue;
        }
        /** @var ParamArrayItemType $itemType */
        $itemType = $attr->newInstance();
        $array    = [];
        foreach ($propertyValue as $item) {
            $array[] = $this->convertPropertyValue($itemType->class, $item);
        }

        return $array;
    }

    /**
     * 自定义类型转换
     *
     * @author XJ.
     * @Date   2025/10/23 星期四
     *
     * @param string $propertyClass
     * @param mixed  $propertyValue
     *
     * @return \BackedEnum|AbstractParam|mixed|void
     */
    private function convertPropertyValue(string $propertyClass, mixed $propertyValue)
    {
        // 本身转换
        if ($propertyValue instanceof $propertyClass) {
            return $propertyValue;
        }

        // 枚举兼容
        if (is_subclass_of($propertyClass, \BackedEnum::class)) {
            return $propertyClass::tryFrom($propertyValue) ?? $propertyValue;
        }

        // 参数对象兼容
        if (is_subclass_of($propertyClass, AbstractParam::class)) {
            return new $propertyClass($propertyValue);
        }

        // 自定义类型兼容
        if (!is_null($newPropertyValue = $this->convertPropertyValueByYourType($propertyClass, $propertyValue))) {
            return $newPropertyValue;
        }

        return $propertyValue;
    }

    /**
     * 自定义类型转换
     *
     * @author XJ.
     * @Date   2025/10/23 星期四
     *
     * @param string $propertyClass 属性类
     * @param mixed  $propertyValue 属性值
     *
     * @return mixed
     */
    protected function convertPropertyValueByYourType(string $propertyClass, mixed $propertyValue): mixed
    {
        // todo: 继承类自己兼容更多类型
        // like:
        // if (is_subclass_of($propertyClass, YourClass::class)) {
        //     return new $propertyClass($propertyValue);
        // }
        return null;
    }

    /**
     * 批量创建自身
     * Created by XJ.
     * Date: 2021/11/15
     *
     * @param array $data 二维数组
     *
     * @return static[]
     */
    public static function batchCreateBySelf(array $data)
    {
        $res = [];
        foreach ($data as $datum) {
            $res[] = new static($datum);
        }

        return $res;
    }

    /**
     * 属性函数化
     *
     * @author XJ.
     * Date: 2023/1/13 0013
     *
     * @param string $name
     * @param array  $arguments
     *
     * @return mixed|void
     */
    public function __call(string $name, array $arguments)
    {

        if (property_exists($this, $name)) {
            return $this->{$name};
        }
        if (get_parent_class(self::class) !== false && method_exists(parent::class, '__call')) {
            return parent::__call($name, $arguments);
        }
    }
}