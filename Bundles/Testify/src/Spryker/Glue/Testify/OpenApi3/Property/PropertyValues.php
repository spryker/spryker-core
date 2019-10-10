<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Glue\Testify\OpenApi3\Property;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

class PropertyValues implements IteratorAggregate, ArrayAccess, Countable
{
    /**
     * @var \Spryker\Glue\Testify\OpenApi3\Property\PropertyValueInterface[]
     */
    protected $properties = [];

    /**
     * @param string $key
     * @param \Spryker\Glue\Testify\OpenApi3\Property\PropertyValueInterface $property
     *
     * @return static
     */
    public function setValue(string $key, PropertyValueInterface $property): self
    {
        if ($this->offsetExists($key)) {
            trigger_error(sprintf('Value is already set before: %s::%s', static::class, $key), E_USER_WARNING);

            return $this;
        }

        $this->properties[$key] = $property;

        return $this;
    }

    // ------------------------------------------------------------------------

    /**
     * @return \Traversable
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->properties);
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->properties);
    }

    /**
     * @param string $offset
     *
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return array_key_exists($offset, $this->properties);
    }

    /**
     * @param string $offset
     *
     * @return \Spryker\Glue\Testify\OpenApi3\Property\PropertyValueInterface
     */
    public function offsetGet($offset): PropertyValueInterface
    {
        return $this->properties[$offset];
    }

    /**
     * @inheritDoc
     */
    public function offsetSet($offset, $value)
    {
        trigger_error(sprintf('Trying to set readonly property: %s::%s', static::class, $offset), E_USER_WARNING);
    }

    /**
     * @inheritDoc
     */
    public function offsetUnset($offset)
    {
        trigger_error(sprintf('Trying to unset readonly property: %s::%s', static::class, $offset), E_USER_WARNING);
    }
}
