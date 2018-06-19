<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitStorage\Communication\Plugin\Event\Subscriber;

use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductPackagingUnit\Dependency\ProductPackagingUnitEvents;
use Spryker\Zed\ProductPackagingUnitStorage\Communication\Plugin\Event\Listener\ProductPackagingLeadProductStorageListener;
use Spryker\Zed\ProductPackagingUnitStorage\Communication\Plugin\Event\Listener\ProductPackagingUnitPublishStorageListener;
use Spryker\Zed\ProductPackagingUnitStorage\Communication\Plugin\Event\Listener\ProductPackagingUnitTypePublishStorageListener;

/**
 * @method \Spryker\Zed\ProductPackagingUnitStorage\Communication\ProductPackagingUnitStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductPackagingUnitStorage\Business\ProductPackagingUnitStorageFacadeInterface getFacade()
 */
class ProductPackagingUnitStorageEventSubscriber extends AbstractPlugin implements EventSubscriberInterface
{
    /**
     * @api
     *
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return \Spryker\Zed\Event\Dependency\EventCollectionInterface
     */
    public function getSubscribedEvents(EventCollectionInterface $eventCollection): EventCollectionInterface
    {
        $this->addProductAbstractPublishStorageListener($eventCollection);
        $this->addProductAbstractUnpublishStorageListener($eventCollection);

        return $eventCollection;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductAbstractPublishStorageListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(ProductPackagingUnitEvents::PRODUCT_PACKAGING_UNIT_PUBLISH, new ProductPackagingUnitPublishStorageListener());

        $eventCollection->addListenerQueued(ProductPackagingUnitEvents::ENTITY_SPY_PRODUCT_PACKAGING_UNIT_TYPE_CREATE, new ProductPackagingUnitTypePublishStorageListener());
        $eventCollection->addListenerQueued(ProductPackagingUnitEvents::ENTITY_SPY_PRODUCT_PACKAGING_UNIT_TYPE_UPDATE, new ProductPackagingUnitTypePublishStorageListener());

        $eventCollection->addListenerQueued(ProductPackagingUnitEvents::ENTITY_SPY_PRODUCT_PACKAGING_UNIT_CREATE, new ProductPackagingUnitPublishStorageListener());
        $eventCollection->addListenerQueued(ProductPackagingUnitEvents::ENTITY_SPY_PRODUCT_PACKAGING_UNIT_UPDATE, new ProductPackagingUnitPublishStorageListener());

        $eventCollection->addListenerQueued(ProductPackagingUnitEvents::ENTITY_SPY_PRODUCT_PACKAGING_LEAD_PRODUCT_CREATE, new ProductPackagingLeadProductStorageListener());
        $eventCollection->addListenerQueued(ProductPackagingUnitEvents::ENTITY_SPY_PRODUCT_PACKAGING_LEAD_PRODUCT_UPDATE, new ProductPackagingLeadProductStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductAbstractUnpublishStorageListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(ProductPackagingUnitEvents::PRODUCT_PACKAGING_UNIT_UNPUBLISH, new ProductPackagingUnitPublishStorageListener());

        $eventCollection->addListenerQueued(ProductPackagingUnitEvents::ENTITY_SPY_PRODUCT_PACKAGING_UNIT_TYPE_DELETE, new ProductPackagingUnitTypePublishStorageListener());

        $eventCollection->addListenerQueued(ProductPackagingUnitEvents::ENTITY_SPY_PRODUCT_PACKAGING_UNIT_DELETE, new ProductPackagingUnitPublishStorageListener());

        $eventCollection->addListenerQueued(ProductPackagingUnitEvents::ENTITY_SPY_PRODUCT_PACKAGING_LEAD_PRODUCT_DELETE, new ProductPackagingLeadProductStorageListener());
    }
}
