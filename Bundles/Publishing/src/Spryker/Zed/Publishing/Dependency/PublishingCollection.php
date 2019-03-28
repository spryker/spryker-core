<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Publishing\Dependency;

use ArrayIterator;
use Spryker\Zed\PublishingExtension\Dependency\PublishingCollectionInterface;
use Spryker\Zed\PublishingExtension\Dependency\PublishingPluginInterface;

class PublishingCollection implements PublishingCollectionInterface
{
    /**
     * @var array
     */
    protected $eventListeners = [];

    /**
     * @param string $eventName
     * @param PublishingPluginInterface $eventHandler
     *
     * @return $this|PublishingCollectionInterface
     */
    public function addPublishingPlugin(string $eventName, PublishingPluginInterface $eventHandler)
    {
        $this->add($eventName, $eventHandler);

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
     * @param PublishingPluginInterface $eventHandler
     * @param bool $isHandledInQueue
     * @param int $priority
     * @param null $queuePoolName
     *
     * @return void
     */
    protected function add($eventName, PublishingPluginInterface $eventHandler)
    {
        if (!$this->has($eventName)) {
            $this->eventListeners[$eventName] = [];
        }

        $this->eventListeners[$eventName][] = $eventHandler;
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
                    'Could not find publishing for event "%s". You have to add it to PublishingDependencyProvider.',
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
