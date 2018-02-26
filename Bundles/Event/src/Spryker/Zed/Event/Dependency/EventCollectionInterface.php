<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Event\Dependency;

use ArrayAccess;
use IteratorAggregate;
use Spryker\Zed\Event\Dependency\Plugin\EventBaseHandlerInterface;

interface EventCollectionInterface extends ArrayAccess, IteratorAggregate
{
    /**
     * @param string $eventName
     * @param \Spryker\Zed\Event\Dependency\Plugin\EventBaseHandlerInterface $eventHandler
     * @param string|null $queuePoolName
     * @param int|null $priority
     *
     * @return $this
     */
    public function addListener($eventName, EventBaseHandlerInterface $eventHandler, $queuePoolName = null, $priority = 0);

    /**
     * @param string $eventName
     * @param \Spryker\Zed\Event\Dependency\Plugin\EventBaseHandlerInterface $eventHandler
     * @param string|null $queuePoolName
     * @param int|null $priority
     *
     * @return $this
     */
    public function addListenerQueued($eventName, EventBaseHandlerInterface $eventHandler, $queuePoolName = null, $priority = 0);

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
     * @return \Spryker\Zed\Event\Dependency\Plugin\EventHandlerInterface[]|\SplPriorityQueue
     */
    public function get($eventName);
}
