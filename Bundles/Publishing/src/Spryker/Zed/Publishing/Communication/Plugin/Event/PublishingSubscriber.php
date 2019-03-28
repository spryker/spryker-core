<?php
/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Publishing\Communication\Plugin\Event;

use Spryker\Zed\AvailabilityStorage\Communication\Plugin\Publishing\AvailabilityStoragePublishingRegistry;
use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\GlossaryStorage\Communication\Plugin\Publishing\GlossaryStoragePublishingRegistry;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Publishing\Dependency\PublishingCollection;
use Spryker\Zed\Publishing\Dependency\PublishingRegistryCollection;

class PublishingSubscriber extends AbstractPlugin implements EventSubscriberInterface
{

    /**
     * @api
     *
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return \Spryker\Zed\Event\Dependency\EventCollectionInterface
     */
    public function getSubscribedEvents(EventCollectionInterface $eventCollection)
    {
        // This will come from DP
        $publishingRegistryCollection = new PublishingRegistryCollection();
        $publishingRegistryCollection->add( new GlossaryStoragePublishingRegistry());
        $publishingRegistryCollection->add( new AvailabilityStoragePublishingRegistry());
        $publishingListeners = [];

        foreach ($publishingRegistryCollection as $publishingRegistry) {
            $publishingListeners[] = $publishingRegistry->getRegisteredPublishingCollection(new PublishingCollection());
        }

        foreach ($publishingListeners as $publishingEventCollection) {
            foreach ($publishingEventCollection as $eventName => $listeners) {
                foreach ($listeners as $listener) {
                    $eventCollection->addListenerQueued($eventName, $listener);
                }
            }
        }

        return $eventCollection;
    }
}
