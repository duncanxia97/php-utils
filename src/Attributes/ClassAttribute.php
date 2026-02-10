<?php
/**
 * @author XJ.
 * @Date   2026/2/9
 */

namespace Fatbit\Utils\Attributes;

use ReflectionClass;

class ClassAttribute
{

    protected ReflectionClass $reflect;

    public function __construct(string|object $classOrObject)
    {
        $this->reflect = new ReflectionClass($classOrObject);

    }

    /**
     * 获取反射注解
     *
     * @author XJ.
     * @Date   2026/2/9
     * @template T
     *
     * @param string|class-string<T> $attributeClass
     *
     * @return \ReflectionAttribute<T>
     */
    protected function getReflectAttributes(?string $attributeClass = null): array
    {
        return $this->reflect->getAttributes($attributeClass);
    }


    /**
     * 获取注解对象
     *
     * @author XJ.
     * @Date   2026/2/9
     * @template T
     *
     * @param ReflectionClass|\ReflectionMethod|\ReflectionProperty|\ReflectionClassConstant $reflect
     * @param string|null|class-string<T>                                                    $attributeClass
     * @param int                                                                            $get
     *
     * @return object|null
     */
    protected function getAttributeObject(\ReflectionClass|\ReflectionMethod|\ReflectionProperty|\ReflectionClassConstant $reflect, ?string $attributeClass = null, int $get = 0): ?object
    {
        return ($reflect->getAttributes($attributeClass)[$get] ?? null)?->newInstance();
    }

    /**
     * 获取注解对象
     *
     * @author XJ.
     * @Date   2026/2/9
     * @template T
     *
     * @param \ReflectionProperty|ReflectionClass $reflect
     * @param string|null|class-string<T>         $attributeClass
     *
     * @return array|T[]
     */
    protected function getAttributesObjects(\ReflectionClass|\ReflectionMethod|\ReflectionProperty|\ReflectionClassConstant $reflect, ?string $attributeClass = null): array
    {
        return array_map(
            function (\ReflectionAttribute $attribute) {
                return $attribute->newInstance();
            },
            $reflect->getAttributes($attributeClass),
        );
    }

    /**
     * 获取反射属性
     *
     * @author XJ.
     * @Date   2026/2/9
     *
     * @param string|null $name
     *
     * @return \ReflectionProperty|array|\ReflectionProperty[]
     * @throws \ReflectionException
     */
    protected function getReflectProperties(?string $name = null): \ReflectionProperty|array
    {
        if ($name) {
            return $this->reflect->getProperty($name);
        }

        return $this->reflect->getProperties();
    }

    /**
     * 获取反射方法
     *
     * @author XJ.
     * @Date   2026/2/9
     *
     * @param string|null $name 函数名称
     *
     * @return \ReflectionMethod|array|\ReflectionMethod[]
     * @throws \ReflectionException
     */
    protected function getReflectMethods(?string $name = null): \ReflectionMethod|array
    {
        if ($name) {
            return $this->reflect->getMethod($name);
        }

        return $this->reflect->getMethods();
    }

    /**
     * 获取反射常量
     *
     * @author XJ.
     * @Date   2026/2/9
     *
     * @param string|null $name
     *
     * @return \ReflectionClassConstant|array|\ReflectionClassConstant[]
     */
    protected function getReflectConstants(?string $name = null): \ReflectionClassConstant|array
    {
        if ($name) {
            return $this->reflect->getReflectionConstants($name);
        }

        return $this->reflect->getReflectionConstants();
    }

    /**
     * 获取类的注解对象
     *
     * @author XJ.
     * @Date   2026/2/9
     * @template T
     *
     * @param string|null|class-string<T> $attributeClass
     *
     * @return array|T[]
     */
    public function getAttributes(?string $attributeClass = null): array
    {
        return $this->getAttributesObjects($this->reflect, $attributeClass);
    }

    /**
     * 获取类的注解对象
     *
     * @author        XJ.
     * @Date          2026/2/9
     * @template T
     *
     * @param string|class-string<T> $attributeClass
     * @param int                    $get
     *
     * @return object|null
     */
    public function getAttribute(string $attributeClass, int $get = 0): ?object
    {
        return $this->getAttributeObject($this->reflect, $attributeClass, $get);
    }

    /**
     * 获取属性的注解对象
     *
     * @author XJ.
     * @Date   2026/2/9
     * @template A
     * @template B
     *
     * @param string|null|A               $name
     * @param string|null|class-string<B> $attributeClass
     *
     * @return array|array<A, B[]>
     * @throws \ReflectionException
     */
    public function getPropertiesAttributes(?string $name = null, ?string $attributeClass = null): array
    {
        $properties = $this->getReflectProperties($name);
        if ($name) {
            return [$properties->name => $this->getAttributesObjects($properties, $attributeClass)];
        }

        $res = [];
        foreach ($properties as $property) {
            $res[$property] = $this->getAttributesObjects($property, $attributeClass);
        }

        return $res;
    }

    /**
     * 获取属性的注解对象
     *
     * @author XJ.
     * @Date   2026/2/9
     * @template T
     *
     * @param string                      $name
     * @param string|null|class-string<T> $attributeClass
     * @param int                         $get
     *
     * @return object|null
     * @throws \ReflectionException
     */
    public function getPropertyAttribute(string $name, ?string $attributeClass = null, int $get = 0): ?object
    {
        $property = $this->getReflectProperties($name);
        if (!$property) {
            return null;
        }

        return $this->getAttributeObject($property, $attributeClass, $get);
    }

    /**
     * 获取函数的注解对象
     *
     * @author XJ.
     * @Date   2026/2/9
     * @template A
     * @template B
     *
     * @param string|null|A               $name
     * @param string|null|class-string<B> $attributeClass
     *
     * @return array|array<A, B[]>
     * @throws \ReflectionException
     */
    public function getMethodsAttributes(?string $name = null, ?string $attributeClass = null): array
    {
        $methods = $this->getReflectMethods($name);
        if ($name) {
            return [$methods->name => $this->getAttributesObjects($methods, $attributeClass)];
        }
        $res = [];
        foreach ($methods as $method) {
            $res[$method] = $this->getAttributesObjects($method, $attributeClass);
        }

        return $res;
    }

    /**
     * 获取函数的注解对象
     *
     * @author XJ.
     * @Date   2026/2/9
     * @template T
     *
     * @param string                      $name
     * @param string|null|class-string<T> $attributeClass
     * @param int                         $get
     *
     * @return object|null
     * @throws \ReflectionException
     */
    public function getMethodAttribute(string $name, ?string $attributeClass = null, int $get = 0): ?object
    {
        $method = $this->getReflectMethods($name);
        if (!$method) {
            return null;
        }

        return $this->getAttributeObject($method, $attributeClass, $get);
    }

    /**
     * 获取常量的注解对象
     *
     * @author XJ.
     * @Date   2026/2/9
     * @template A
     * @template B
     *
     * @param string|null|A               $name
     * @param string|null|class-string<B> $attributeClass
     *
     * @return array|array<A, B[]>
     * @throws \ReflectionException
     */
    public function getConstantsAttributes(?string $name = null, ?string $attributeClass = null): array
    {
        $constants = $this->getReflectConstants($name);
        if ($name) {
            return [$constants->name => $this->getAttributesObjects($constants, $attributeClass)];
        }
        $res = [];
        foreach ($constants as $constant) {
            $res[$constant] = $this->getAttributesObjects($constant, $attributeClass);
        }

        return $res;
    }

    /**
     * 获取常量的注解对象
     *
     * @author XJ.
     * @Date   2026/2/9
     * @template T
     *
     * @param string                      $name
     * @param string|null|class-string<T> $attributeClass
     * @param int                         $get
     *
     * @return object|null
     */
    public function getConstantAttribute(string $name, ?string $attributeClass = null, int $get = 0): ?object
    {
        $constant = $this->getReflectConstants($name);
        if (!$constant) {
            return null;
        }

        return $this->getAttributeObject($constant, $attributeClass, $get);
    }

}