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
use Spryker\Zed\Event\Dependency\Plugin\EventListenerInterface;

class EventCollection implements EventCollectionInterface
{

    /**
     * @var array|\SplPriorityQueue[]
     */
    protected $eventListeners = [];

    /**
     * @param string $eventName
     * @param \Spryker\Zed\Event\Dependency\Plugin\EventListenerInterface $eventListener
     * @param int $priority
     *
     * @return $this
     */
    public function addListener($eventName, EventListenerInterface $eventListener, $priority = 0)
    {
        $this->add($eventName, $eventListener, false, $priority);

        return $this;
    }

    /**
     * @param string $eventName
     * @param \Spryker\Zed\Event\Dependency\Plugin\EventListenerInterface $eventListener
     * @param int $priority
     *
     * @return $this
     */
    public function addListenerQueued($eventName, EventListenerInterface $eventListener, $priority = 0)
    {
        $this->add($eventName, $eventListener, true, $priority);

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
     * @param \Spryker\Zed\Event\Dependency\Plugin\EventListenerInterface $eventListener
     * @param bool $isHandledInQueue
     * @param int $priority
     *
     * @return void
     */
    protected function add($eventName, EventListenerInterface $eventListener, $isHandledInQueue = false, $priority = 0)
    {
        if (!$this->has($eventName)) {
            $this->eventListeners[$eventName] = new SplPriorityQueue();
        }

        $this->eventListeners[$eventName]->insert(new EventListenerContext($eventListener, $isHandledInQueue), $priority);
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
        if (!isset($this->eventListeners[$eventName]) || count($this->eventListeners[$eventName]) == 0) {
            throw new EventListenerNotFoundException(
                sprintf(
                    'Could not find event listeners for event "%s". You have to add it to EventDependencyProvider.',
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
     * @since 5.0.0
     *
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     *
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
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
     * @since 5.0.0
     *
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
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
     * @since 5.0.0
     *
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
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
     * @since 5.0.0
     *
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
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
     * @since 5.0.0
     *
     * @return \Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     */
    public function getIterator()
    {
        return new ArrayIterator($this->eventListeners);
    }

}
