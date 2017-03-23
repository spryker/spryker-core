<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Event\Business\Subscriber;

use Spryker\Zed\Event\Dependency\EventCollectionInterface;

class SubscriberMerger implements SubscriberMergerInterface
{

    /**
     * @var array|\Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface[]
     */
    protected $subscribers;

    /**
     * @param array|\Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface[] $subscribers
     */
    public function __construct(array $subscribers)
    {
        $this->subscribers = $subscribers;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return \Spryker\Zed\Event\Dependency\EventCollectionInterface
     */
    public function mergeSubscribersWith(EventCollectionInterface $eventCollection)
    {
        foreach ($this->subscribers as $subscriber) {
            $eventCollection = $subscriber->getSubscribedEvents($eventCollection);
        }

        return $eventCollection;
    }

}
