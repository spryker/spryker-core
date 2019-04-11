<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityStorage\Communication\Plugin\Publishing;

use Spryker\Zed\Availability\Dependency\AvailabilityEvents;
use Spryker\Zed\AvailabilityStorage\Communication\Plugin\Publishing\Availability\AvailabilityAbstractPublisher;
use Spryker\Zed\AvailabilityStorage\Communication\Plugin\Publishing\Availability\AvailabilityPublisher;
use Spryker\Zed\AvailabilityStorage\Communication\Plugin\Publishing\Availability\AvailabilityAbstractUnpublisher;
use Spryker\Zed\AvailabilityStorage\Communication\Plugin\Publishing\Product\AvailabilityProductPublisher;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Product\Dependency\ProductEvents;
use Spryker\Zed\PublishingExtension\Dependency\PublisherEventRegistryInterface;
use Spryker\Zed\PublishingExtension\Dependency\PublisherRegistryInterface;

/**
 * @method \Spryker\Zed\AvailabilityStorage\Communication\AvailabilityStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\AvailabilityStorage\Business\AvailabilityStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\AvailabilityStorage\AvailabilityStorageConfig getConfig()
 * @method \Spryker\Zed\AvailabilityStorage\Persistence\AvailabilityStorageQueryContainerInterface getQueryContainer()
 */
class AvailabilityStoragePublisherRegistry extends AbstractPlugin implements PublisherRegistryInterface
{

    /**
     * @param PublisherEventRegistryInterface $publisherEventRegistry
     *
     * @return PublisherEventRegistryInterface
     */
    public function getPublisherEventRegistry(PublisherEventRegistryInterface $publisherEventRegistry)
    {
        $publisherEventRegistry->register(AvailabilityEvents::AVAILABILITY_ABSTRACT_PUBLISH, new AvailabilityAbstractPublisher());
        $publisherEventRegistry->register(AvailabilityEvents::ENTITY_SPY_AVAILABILITY_ABSTRACT_CREATE, new AvailabilityAbstractPublisher());
        $publisherEventRegistry->register(AvailabilityEvents::ENTITY_SPY_AVAILABILITY_ABSTRACT_UPDATE, new AvailabilityAbstractPublisher());
        $publisherEventRegistry->register(AvailabilityEvents::ENTITY_SPY_AVAILABILITY_UPDATE, new AvailabilityPublisher());
        $publisherEventRegistry->register(AvailabilityEvents::AVAILABILITY_ABSTRACT_UNPUBLISH, new AvailabilityAbstractUnpublisher());
        $publisherEventRegistry->register(AvailabilityEvents::ENTITY_SPY_AVAILABILITY_ABSTRACT_DELETE, new AvailabilityAbstractUnpublisher());
        $publisherEventRegistry->register(ProductEvents::ENTITY_SPY_PRODUCT_UPDATE, new AvailabilityProductPublisher());

        return $publisherEventRegistry;
    }
}
