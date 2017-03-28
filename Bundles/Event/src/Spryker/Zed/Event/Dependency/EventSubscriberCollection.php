<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Event\Dependency;

use ArrayAccess;
use ArrayIterator;
use IteratorAggregate;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;

class EventSubscriberCollection implements ArrayAccess, IteratorAggregate, EventSubscriberCollectionInterface
{

    /**
     * @var array|\Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface[]
     */
    protected $eventSubscribers = [];

    /**
     * @param \Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface $eventSubscriber
     *
     * @return void
     */
    public function add(EventSubscriberInterface $eventSubscriber)
    {
        $this->eventSubscribers[] = $eventSubscriber;
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
        return isset($this->eventSubscribers[$offset]);
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
     * @return array|\Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface
     */
    public function offsetGet($offset)
    {
        return $this->eventSubscribers[$offset];
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
     * @param mixed|\Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface $value <p>
     * The value to set.
     * </p>
     *
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->eventSubscribers[$offset] = $value;
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
        unset($this->eventSubscribers[$offset]);
    }

    /**
     * Retrieve an external iterator
     *
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     *
     * @since 5.0.0
     *
     * @return \Traversable|\Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface[]
     */
    public function getIterator()
    {
        return new ArrayIterator($this->eventSubscribers);
    }

}
