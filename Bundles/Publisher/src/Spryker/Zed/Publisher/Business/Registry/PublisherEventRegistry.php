<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Publisher\Business\Registry;

use ArrayIterator;
use Exception;
use Spryker\Zed\PublisherExtension\Dependency\PublisherEventRegistryInterface;
use Traversable;

class PublisherEventRegistry implements PublisherEventRegistryInterface
{
    /**
     * @var array|\SplPriorityQueue[]
     */
    protected $publisherPlugins = [];

    /**
     * @param string $eventName
     * @param string $publisherPluginClassName
     *
     * @return $this
     */
    public function register(string $eventName, string $publisherPluginClassName)
    {
        $this->add($eventName, $publisherPluginClassName);

        return $this;
    }

    /**
     * @param string $eventName
     * @param string $publisherPluginClassName
     *
     * @return void
     */
    protected function add(string $eventName, string $publisherPluginClassName): void
    {
        if (!$this->has($eventName)) {
            $this->publisherPlugins[$eventName] = [];
        }

        $this->publisherPlugins[$eventName][] = $publisherPluginClassName;
    }

    /**
     * @param string $eventName
     *
     * @return bool
     */
    protected function has(string $eventName): bool
    {
        return isset($this->publisherPlugins[$eventName]);
    }

    /**
     * @param string $eventName
     *
     * @throws \Exception
     *
     * @return \SplPriorityQueue|\Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherPluginInterface
     */
    protected function get(string $eventName)
    {
        if (!isset($this->publisherPlugins[$eventName]) || count($this->publisherPlugins[$eventName]) === 0) {
            throw new Exception(
                sprintf(
                    'Could not find publisher for event "%s". You have to add a publisher for the event "%s" to PublisherDependencyProvider::getPublisherRegistryPlugins()',
                    $eventName,
                    $eventName
                )
            );
        }

        return $this->publisherPlugins[$eventName];
    }

    /**
     * Retrieve an external iterator
     *
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     *
     * @return \Traversable
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->publisherPlugins);
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
        return $this->has($offset);
    }

    /**
     * Offset to retrieve
     *
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     *
     * @param mixed $offset
     *
     * @return array|\SplPriorityQueue
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * Offset to set
     *
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     *
     * @param mixed $offset
     * @param mixed $value
     *
     * @return void
     */
    public function offsetSet($offset, $value): void
    {
        $this->add($value, $offset);
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
        unset($this->publisherPlugins[$offset]);
    }
}
