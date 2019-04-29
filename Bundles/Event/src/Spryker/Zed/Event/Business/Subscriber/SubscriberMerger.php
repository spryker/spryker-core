<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Event\Business\Subscriber;

use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\EventSubscriberCollectionInterface;

class SubscriberMerger implements SubscriberMergerInterface
{
    /**
     * @var \Spryker\Zed\Event\Dependency\EventSubscriberCollectionInterface
     */
    protected $eventSubscriberCollection;

    /**
     * @var \Spryker\Zed\Event\Dependency\EventCollectionInterface|null
     */
    protected static $eventCollectionBuffer;

    /**
     * @param \Spryker\Zed\Event\Dependency\EventSubscriberCollectionInterface $eventSubscriberCollection
     */
    public function __construct(EventSubscriberCollectionInterface $eventSubscriberCollection)
    {
        $this->eventSubscriberCollection = $eventSubscriberCollection;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return \Spryker\Zed\Event\Dependency\EventCollectionInterface
     */
    public function mergeSubscribersWith(EventCollectionInterface $eventCollection)
    {
        if (static::$eventCollectionBuffer === null) {
            foreach ($this->eventSubscriberCollection as $subscriber) {
                $eventCollection = $subscriber->getSubscribedEvents($eventCollection);
            }

            static::$eventCollectionBuffer = $eventCollection;
        }

        return static::$eventCollectionBuffer;
    }
}
