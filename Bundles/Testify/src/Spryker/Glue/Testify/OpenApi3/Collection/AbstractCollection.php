<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Glue\Testify\OpenApi3\Collection;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use Spryker\Glue\Testify\OpenApi3\Property\PropertyDefinition;
use Spryker\Glue\Testify\OpenApi3\Property\PropertyValueInterface;
use Spryker\Glue\Testify\OpenApi3\SchemaFieldInterface;

abstract class AbstractCollection implements CollectionInterface, IteratorAggregate, ArrayAccess, Countable
{
    /**
     * @var \Spryker\Glue\Testify\OpenApi3\Property\PropertyValueInterface[]
     */
    protected $elements = [];

    /**
     * @inheritDoc
     */
    abstract public function getElementDefinition(): PropertyDefinition;

    /**
     * @inheritDoc
     */
    public function hydrate($content): SchemaFieldInterface
    {
        $this->elements = [];

        foreach ((array)$content as $key => $element) {
            if ($element instanceof PropertyValueInterface === false) {
                trigger_error(
                    sprintf(
                        'Invalid argument for hydration: expected %s, but %s found',
                        PropertyValueInterface::class,
                        get_class($element)
                    ),
                    E_USER_WARNING
                );

                continue;
            }

            $this->elements[$key] = $element;
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function export(): CollectionInterface
    {
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        $result = [];

        foreach ($this as $key => $value) {
            $result[$key] = $value;
        }

        return $result;
    }

    // ----------------------------------------------------

    /**
     * @inheritDoc
     */
    public function getIterator()
    {
        return new ArrayIterator(array_map(function (PropertyValueInterface $element) {
            return $element->getValue()->export();
        }, $this->elements));
    }

    /**
     * @inheritDoc
     */
    public function count()
    {
        return count($this->elements);
    }

    /**
     * @inheritDoc
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->elements);
    }

    /**
     * @inheritDoc
     */
    public function offsetGet($offset)
    {
        if (!$this->offsetExists($offset)) {
            trigger_error(sprintf('Accessing non-existing offset: %s::%s', static::class, $offset), E_USER_WARNING);

            $class = $this->getElementDefinition()->getType();

            return new $class();
        }

        return $this->elements[$offset]->getValue()->export();
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

    /**
     * @inheritDoc
     */
    public function __debugInfo()
    {
        return $this->elements;
    }
}
