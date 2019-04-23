<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Publisher\Communication\Collection;

use ArrayIterator;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherRegistryPluginInterface;

class PublisherRegistryCollection implements PublisherRegistryCollectionInterface
{
    /**
     * @var \Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherRegistryPluginInterface[]
     */
    protected $registryCollection = [];

    /**
     * @param \Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherRegistryPluginInterface $publisherRegistry
     *
     * @return void
     */
    public function add(PublisherRegistryPluginInterface $publisherRegistry): void
    {
        $this->registryCollection[] = $publisherRegistry;
    }

    /**
     * Whether a offset exists
     *
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     *
     * @param mixed $offset
     *
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return isset($this->registryCollection[$offset]);
    }

    /**
     * Offset to retrieve
     *
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     *
     * @param mixed $offset
     *
     * @return array|\Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherRegistryPluginInterface
     */
    public function offsetGet($offset)
    {
        return $this->registryCollection[$offset];
    }

    /**
     * Offset to set
     *
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     *
     * @param mixed $offset
     * @param mixed|\Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface $value
     *
     * @return void
     */
    public function offsetSet($offset, $value): void
    {
        $this->registryCollection[$offset] = $value;
    }

    /**
     * Offset to unset
     *
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     *
     * @param mixed $offset
     *
     * @return void
     */
    public function offsetUnset($offset): void
    {
        unset($this->registryCollection[$offset]);
    }

    /**
     * Retrieve an external iterator
     *
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     *
     * @return \Traversable|\Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface[]
     */
    public function getIterator()
    {
        return new ArrayIterator($this->registryCollection);
    }
}
