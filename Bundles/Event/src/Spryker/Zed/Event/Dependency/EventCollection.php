<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Event\Dependency;

use ArrayIterator;
use SplPriorityQueue;
use Spryker\Zed\Event\Business\Dispatcher\EventListenerContext;
use Spryker\Zed\Event\Business\Exception\EventListenerNotFoundException;
use Spryker\Zed\Event\Dependency\Plugin\EventBaseHandlerInterface;

class EventCollection implements EventCollectionInterface
{
    /**
     * @var array<\SplPriorityQueue<mixed, \Spryker\Zed\Event\Business\Dispatcher\EventListenerContextInterface>>
     */
    protected $eventListeners = [];

    /**
     * @param string $eventName
     * @param \Spryker\Zed\Event\Dependency\Plugin\EventBaseHandlerInterface $eventHandler
     * @param int|null $priority
     * @param string|null $queuePoolName
     * @param string|null $eventQueueName
     *
     * @return $this
     */
    public function addListener($eventName, EventBaseHandlerInterface $eventHandler, $priority = 0, $queuePoolName = null, $eventQueueName = null)
    {
        $this->add($eventName, $eventHandler, false, $priority, $queuePoolName, $eventQueueName);

        return $this;
    }

    /**
     * @param string $eventName
     * @param \Spryker\Zed\Event\Dependency\Plugin\EventBaseHandlerInterface $eventHandler
     * @param int|null $priority
     * @param string|null $queuePoolName
     * @param string|null $eventQueueName
     *
     * @return $this
     */
    public function addListenerQueued($eventName, EventBaseHandlerInterface $eventHandler, $priority = 0, $queuePoolName = null, $eventQueueName = null)
    {
        $this->add($eventName, $eventHandler, true, $priority, $queuePoolName, $eventQueueName);

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
     * @param string $eventName
     * @param \Spryker\Zed\Event\Dependency\Plugin\EventBaseHandlerInterface $eventHandler
     * @param bool $isHandledInQueue
     * @param int|null $priority
     * @param string|null $queuePoolName
     * @param string|null $eventQueueName
     *
     * @return void
     */
    protected function add(
        $eventName,
        EventBaseHandlerInterface $eventHandler,
        $isHandledInQueue = false,
        $priority = 0,
        $queuePoolName = null,
        $eventQueueName = null
    ) {
        if (!$this->has($eventName)) {
            $this->eventListeners[$eventName] = new SplPriorityQueue();
        }

        $this->eventListeners[$eventName]->insert(new EventListenerContext($eventHandler, $isHandledInQueue, $queuePoolName, $eventQueueName), $priority);
    }

    /**
     * @param string $eventName
     *
     * @throws \Spryker\Zed\Event\Business\Exception\EventListenerNotFoundException
     *
     * @return \SplPriorityQueue<mixed, \Spryker\Zed\Event\Business\Dispatcher\EventListenerContextInterface>
     */
    public function get($eventName)
    {
        if (!isset($this->eventListeners[$eventName]) || count($this->eventListeners[$eventName]) === 0) {
            throw new EventListenerNotFoundException(
                sprintf(
                    'Could not find event listeners for event "%s". You have to add it to EventDependencyProvider.',
                    $eventName,
                ),
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
    #[\ReturnTypeWillChange]
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
     * @return \SplPriorityQueue<mixed, \Spryker\Zed\Event\Business\Dispatcher\EventListenerContextInterface>|array
     */
    #[\ReturnTypeWillChange]
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
    #[\ReturnTypeWillChange]
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
    #[\ReturnTypeWillChange]
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
    #[\ReturnTypeWillChange]
    public function getIterator()
    {
        return new ArrayIterator($this->eventListeners);
    }
}
