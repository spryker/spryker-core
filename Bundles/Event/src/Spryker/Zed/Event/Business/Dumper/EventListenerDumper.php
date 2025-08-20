<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\Event\Business\Dumper;

use Spryker\Zed\Event\Business\Subscriber\SubscriberMergerInterface;
use Spryker\Zed\Event\Dependency\EventCollectionInterface;

class EventListenerDumper implements EventListenerDumperInterface
{
    /**
     * @param \Spryker\Zed\Event\Business\Subscriber\SubscriberMergerInterface $subscriberMerger
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     */
    public function __construct(protected SubscriberMergerInterface $subscriberMerger, protected EventCollectionInterface $eventCollection)
    {
    }

    /**
     * @return array<int|string, array<string, array<bool|string|null>>>
     */
    public function dump(): array
    {
        $eventListeners = $this->subscriberMerger->mergeSubscribersWith(
            $this->eventCollection,
        );

        $dumpedListeners = [];

        foreach ($eventListeners as $eventName => $listeners) {
            if (!isset($dumpedListeners[$eventName])) {
                $dumpedListeners[$eventName] = [];
            }

            /** @var \Spryker\Zed\Event\Business\Dispatcher\EventListenerContextInterface $listener */
            foreach ($listeners as $listener) {
                $listenerName = $listener->getListenerName();

                $listenerIdentifier = $this->getListenerIdentifier($listenerName);

                $listenerContext = [
                    'listener' => $listenerName,
                    'isHandledInQueue' => $listener->isHandledInQueue(),
                    'queueName' => $listener->getEventQueueName(),
                    'queuePoolName' => $listener->getQueuePoolName(),
                ];

                $dumpedListeners[$eventName][$listenerIdentifier] = $listenerContext;
            }
        }

        ksort($dumpedListeners);

        return $dumpedListeners;
    }

    /**
     * @param string $listenerClassName
     *
     * @return string
     */
    protected function getListenerIdentifier(string $listenerClassName): string
    {
        $listenerClassNameFragments = explode('\\', $listenerClassName);

        return sprintf('%s::%s', $listenerClassNameFragments[2], end($listenerClassNameFragments));
    }
}
