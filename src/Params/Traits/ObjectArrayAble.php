<?php
/**
 * @author XJ.
 * @Date   2025/11/6
 */

namespace Fatbit\Utils\Params\Traits;

trait ObjectArrayAble
{
    use ToArrayJson;

    public function &getIterator(): \Iterator
    {
        foreach ((array)$this as $key => &$value) {
            yield $key => & $value;
        }
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->{$offset});
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->{$offset};
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->{$offset} = $value;
    }

    public function offsetUnset(mixed $offset): void
    {
        throw new \Exception('Object array cant unset property');
    }

    public function count(): int
    {
        return count($this->toArray());
    }

}