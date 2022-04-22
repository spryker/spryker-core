<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Event\Dependency;

use ArrayAccess;
use IteratorAggregate;
use Spryker\Zed\Event\Dependency\Plugin\EventBaseHandlerInterface;

/**
 * @extends \ArrayAccess<string, \SplPriorityQueue<mixed, \Spryker\Zed\Event\Business\Dispatcher\EventListenerContextInterface>>
 * @extends \IteratorAggregate<\SplPriorityQueue<mixed, \Spryker\Zed\Event\Business\Dispatcher\EventListenerContextInterface>>
 */
interface EventCollectionInterface extends ArrayAccess, IteratorAggregate
{
    /**
     * @param string $eventName
     * @param \Spryker\Zed\Event\Dependency\Plugin\EventBaseHandlerInterface $eventHandler
     * @param int|null $priority
     * @param string|null $queuePoolName
     * @param string|null $eventQueueName
     *
     * @return $this
     */
    public function addListener($eventName, EventBaseHandlerInterface $eventHandler, $priority = 0, $queuePoolName = null, $eventQueueName = null);

    /**
     * @param string $eventName
     * @param \Spryker\Zed\Event\Dependency\Plugin\EventBaseHandlerInterface $eventHandler
     * @param int|null $priority
     * @param string|null $queuePoolName
     * @param string|null $eventQueueName
     *
     * @return $this
     */
    public function addListenerQueued($eventName, EventBaseHandlerInterface $eventHandler, $priority = 0, $queuePoolName = null, $eventQueueName = null);

    /**
     * @param string $eventName
     *
     * @return bool
     */
    public function has($eventName);

    /**
     * @param string $eventName
     *
     * @throws \Spryker\Zed\Event\Business\Exception\EventListenerNotFoundException
     *
     * @return \SplPriorityQueue<mixed, \Spryker\Zed\Event\Business\Dispatcher\EventListenerContextInterface>
     */
    public function get($eventName);
}
