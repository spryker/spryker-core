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
use Spryker\Zed\ProductPackagingUnitStorage\Communication\Plugin\Event\Listener\ProductConcretePackagingStoragePublishListener;
use Spryker\Zed\ProductPackagingUnitStorage\Communication\Plugin\Event\Listener\ProductConcretePackagingStorageUnpublishListener;
use Spryker\Zed\ProductPackagingUnitStorage\Communication\Plugin\Event\Listener\ProductPackagingUnitTypePublishListener;
use Spryker\Zed\ProductPackagingUnitStorage\Communication\Plugin\Event\Listener\ProductPackagingUnitTypeUnpublishListener;

/**
 * @method \Spryker\Zed\ProductPackagingUnitStorage\Communication\ProductPackagingUnitStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductPackagingUnitStorage\Business\ProductPackagingUnitStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductPackagingUnitStorage\ProductPackagingUnitStorageConfig getConfig()
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
        $this->addProductConcretePublishStorageListener($eventCollection);
        $this->addProductConcreteUnpublishStorageListener($eventCollection);

        return $eventCollection;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductConcretePublishStorageListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(ProductPackagingUnitEvents::PRODUCT_PACKAGING_UNIT_PUBLISH, new ProductPackagingUnitTypePublishListener());

        $eventCollection->addListenerQueued(ProductPackagingUnitEvents::ENTITY_SPY_PRODUCT_PACKAGING_UNIT_TYPE_CREATE, new ProductPackagingUnitTypePublishListener());
        $eventCollection->addListenerQueued(ProductPackagingUnitEvents::ENTITY_SPY_PRODUCT_PACKAGING_UNIT_TYPE_UPDATE, new ProductPackagingUnitTypePublishListener());

    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductConcreteUnpublishStorageListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(ProductPackagingUnitEvents::PRODUCT_PACKAGING_UNIT_UNPUBLISH, new ProductPackagingUnitTypeUnpublishListener());
        $eventCollection->addListenerQueued(ProductPackagingUnitEvents::ENTITY_SPY_PRODUCT_PACKAGING_UNIT_TYPE_DELETE, new ProductPackagingUnitTypeUnpublishListener());

    }
}
