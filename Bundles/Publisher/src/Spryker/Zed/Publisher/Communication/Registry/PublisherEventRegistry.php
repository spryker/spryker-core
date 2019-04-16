<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Publisher\Dependency;

use ArrayIterator;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherEventRegistryInterface;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherPluginInterface;

class PublisherEventRegistry implements PublisherEventRegistryInterface
{
    /**
     * @var array
     */
    protected $eventListeners = [];

    /**
     * @param string $eventName
     * @param PublisherPluginInterface $publisherPlugin
     *
     * @return $this|PublisherEventRegistryInterface
     */
    public function register(string $eventName, PublisherPluginInterface $publisherPlugin)
    {
        $this->add($eventName, $publisherPlugin);

        return $this;
    }

    /**
     * @param string $eventName
     *
     * @return bool
     */
    public function has($eventName)
    {
        return isset($this->eventListeners[$eventName]);
    }

    /**
     * @param $eventName
     * @param PublisherPluginInterface $publisherPlugin
     * @param bool $isHandledInQueue
     * @param int $priority
     * @param null $queuePoolName
     *
     * @return void
     */
    protected function add($eventName, PublisherPluginInterface $publisherPlugin)
    {
        if (!$this->has($eventName)) {
            $this->eventListeners[$eventName] = [];
        }

        $this->eventListeners[$eventName][] = $publisherPlugin;
    }

    /**
     * @param string $eventName
     *
     * @throws \Spryker\Zed\Event\Business\Exception\EventListenerNotFoundException
     *
     * @return \SplPriorityQueue|\Spryker\Zed\Event\Business\Dispatcher\EventListenerContextInterface[]
     */
    public function get($eventName)
    {
        if (!isset($this->eventListeners[$eventName]) || count($this->eventListeners[$eventName]) === 0) {
            throw new \Exception(
                sprintf(
                    'Could not find publisher for event "%s". You have to add it to PublisherDependencyProvider.',
                    $eventName
                )
            );
        }

        return $this->eventListeners[$eventName];
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
    public function offsetExists($offset)
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
    public function offsetSet($offset, $value)
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
    public function offsetUnset($offset)
    {
        unset($this->eventListeners[$offset]);
    }

    /**
     * Retrieve an external iterator
     *
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     *
     * @return \Traversable
     */
    public function getIterator()
    {
        return new ArrayIterator($this->eventListeners);
    }
}
