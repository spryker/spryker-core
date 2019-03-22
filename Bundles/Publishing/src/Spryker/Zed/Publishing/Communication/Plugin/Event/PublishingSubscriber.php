<?php
/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Publishing\Communication\Plugin\Event;

use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\GlossaryStorage\Communication\Plugin\Event\Subscriber\GlossaryStoragePublisherRegistry;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Publisher\Dependency\PublisherRegistryCollection;
use Spryker\Zed\PublishingExtension\Dependency\PublisherCollectionInterface;

class PublishingSubscriber extends AbstractPlugin implements EventSubscriberInterface
{

    /**
     * @var PublisherCollectionInterface
     */
    protected $publisherCollection = [];

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
        $publisherRegistryCollection = new PublisherRegistryCollection();
        $publisherRegistryCollection->add( new GlossaryStoragePublisherRegistry());

        foreach ($publisherRegistryCollection as $publisherRegistry) {
            $this->publisherCollection += $publisherRegistry->getRegisteredPublishers($this->publisherCollection);
        }

        // TODO: remove this debug output
        dump($this->publisherCollection);die;
    }
}
