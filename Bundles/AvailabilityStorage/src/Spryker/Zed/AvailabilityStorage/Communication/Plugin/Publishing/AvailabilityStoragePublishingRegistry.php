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
use Spryker\Zed\PublishingExtension\Dependency\PublishingCollectionInterface;
use Spryker\Zed\PublishingExtension\Dependency\PublishingRegistryInterface;

/**
 * @method \Spryker\Zed\AvailabilityStorage\Communication\AvailabilityStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\AvailabilityStorage\Business\AvailabilityStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\AvailabilityStorage\AvailabilityStorageConfig getConfig()
 * @method \Spryker\Zed\AvailabilityStorage\Persistence\AvailabilityStorageQueryContainerInterface getQueryContainer()
 */
class AvailabilityStoragePublishingRegistry extends AbstractPlugin implements PublishingRegistryInterface
{

    /**
     * @param PublishingCollectionInterface $publishingCollection
     *
     * @return PublishingCollectionInterface
     */
    public function getRegisteredPublishingCollection(PublishingCollectionInterface $publishingCollection)
    {
        $publishingCollection->addPublishingPlugin(AvailabilityEvents::AVAILABILITY_ABSTRACT_PUBLISH, new AvailabilityAbstractPublisher());
        $publishingCollection->addPublishingPlugin(AvailabilityEvents::ENTITY_SPY_AVAILABILITY_ABSTRACT_CREATE, new AvailabilityAbstractPublisher());
        $publishingCollection->addPublishingPlugin(AvailabilityEvents::ENTITY_SPY_AVAILABILITY_ABSTRACT_UPDATE, new AvailabilityAbstractPublisher());
        $publishingCollection->addPublishingPlugin(AvailabilityEvents::ENTITY_SPY_AVAILABILITY_UPDATE, new AvailabilityPublisher());
        $publishingCollection->addPublishingPlugin(AvailabilityEvents::AVAILABILITY_ABSTRACT_UNPUBLISH, new AvailabilityAbstractUnpublisher());
        $publishingCollection->addPublishingPlugin(AvailabilityEvents::ENTITY_SPY_AVAILABILITY_ABSTRACT_DELETE, new AvailabilityAbstractUnpublisher());
        $publishingCollection->addPublishingPlugin(ProductEvents::ENTITY_SPY_PRODUCT_UPDATE, new AvailabilityProductPublisher());

        return $publishingCollection;
    }
}
