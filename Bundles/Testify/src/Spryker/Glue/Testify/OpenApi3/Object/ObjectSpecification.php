<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Glue\Testify\OpenApi3\Object;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use Spryker\Glue\Testify\OpenApi3\Property\PropertyDefinition;

class ObjectSpecification implements IteratorAggregate, ArrayAccess, Countable
{
    /**
     * @var \Spryker\Glue\Testify\OpenApi3\Property\PropertyDefinition[]
     */
    protected $properties = [];

    /**
     * @param string $key
     * @param \Spryker\Glue\Testify\OpenApi3\Property\PropertyDefinition $property
     *
     * @return static
     */
    public function setProperty(string $key, PropertyDefinition $property): self
    {
        if ($this->offsetExists($key)) {
            trigger_error(sprintf('Property is already added before: %s::%s', static::class, $key), E_USER_WARNING);

            return $this;
        }

        $this->properties[$key] = $property;

        return $this;
    }

    // ------------------------------------------------------------------------

    /**
     * @inheritdoc
     */
    public function getIterator()
    {
        return new ArrayIterator($this->properties);
    }

    /**
     * @inheritdoc
     */
    public function count()
    {
        return count($this->properties);
    }

    /**
     * @inheritdoc
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->properties);
    }

    /**
     * @inheritdoc
     */
    public function offsetGet($offset)
    {
        return $this->properties[$offset];
    }

    /**
     * @inheritdoc
     */
    public function offsetSet($offset, $value)
    {
        trigger_error(sprintf('Trying to set readonly property: %s::%s', static::class, $offset), E_USER_WARNING);
    }

    /**
     * @inheritdoc
     */
    public function offsetUnset($offset)
    {
        trigger_error(sprintf('Trying to unset readonly property: %s::%s', static::class, $offset), E_USER_WARNING);
    }
}
