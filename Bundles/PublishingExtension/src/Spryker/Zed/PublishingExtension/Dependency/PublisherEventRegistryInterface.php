<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PublishingExtension\Dependency;

use ArrayAccess;
use IteratorAggregate;

interface PublisherEventRegistryInterface extends ArrayAccess, IteratorAggregate
{

    /**
     * @param string $eventName
     * @param PublisherEventPluginInterface $publishingPlugin
     *
     * @return $this
     */
    public function register(string $eventName, PublisherEventPluginInterface $publishingPlugin);

    /**
     * @deprecated
     *
     * @param string $eventName
     *
     * @return bool
     */
    public function has($eventName);

    /**
     * @deprecated
     *
     * @param string $eventName
     *
     * @throws \Spryker\Zed\Event\Business\Exception\EventListenerNotFoundException
     *
     * @return \SplPriorityQueue|\Spryker\Zed\Event\Business\Dispatcher\EventListenerContextInterface[]
     */
    public function get($eventName);
}
