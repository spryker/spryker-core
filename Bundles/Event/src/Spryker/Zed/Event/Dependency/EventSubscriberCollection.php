<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Event\Dependency;

use ArrayIterator;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;

class EventSubscriberCollection implements EventSubscriberCollectionInterface
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
     * @param mixed $offset
     *
     * @return boolean true on success or false on failure.
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
     * @param mixed $offset
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
     * @param mixed $offset
     * @param mixed|\Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface $value
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
     * @param mixed $offset
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
     * @return \Traversable|\Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface[]
     */
    public function getIterator()
    {
        return new ArrayIterator($this->eventSubscribers);
    }

}
