<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PublishingExtension\Dependency;

use ArrayAccess;
use IteratorAggregate;

interface PublisherCollectionInterface extends ArrayAccess, IteratorAggregate
{

    /**
     * @param string $eventName
     * @param PublisherPluginInterface $publisherPlugin
     *
     * @return $this
     */
    public function addPublisher(string $eventName, PublisherPluginInterface $publisherPlugin);

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
