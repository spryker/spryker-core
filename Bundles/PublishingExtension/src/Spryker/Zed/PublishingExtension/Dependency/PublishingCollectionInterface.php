<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PublishingExtension\Dependency;

use ArrayAccess;
use IteratorAggregate;

interface PublishingCollectionInterface extends ArrayAccess, IteratorAggregate
{

    /**
     * @param string $eventName
     * @param PublishingPluginInterface $publishingPlugin
     *
     * @return $this
     */
    public function registerPlugin(string $eventName, PublishingPluginInterface $publishingPlugin);

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
