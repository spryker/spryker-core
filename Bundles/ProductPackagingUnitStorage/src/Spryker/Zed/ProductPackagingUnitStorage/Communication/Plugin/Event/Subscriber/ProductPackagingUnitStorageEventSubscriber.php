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
use Spryker\Zed\ProductPackagingUnitStorage\Communication\Plugin\Event\Listener\ProductPackagingUnitPublishStorageListener;
use Spryker\Zed\ProductPackagingUnitStorage\Communication\Plugin\Event\Listener\ProductPackagingUnitTypePublishStorageListener;
use Spryker\Zed\ProductPackagingUnitStorage\Communication\Plugin\Event\Listener\ProductPackagingUnitTypeUnpublishStorageListener;
use Spryker\Zed\ProductPackagingUnitStorage\Communication\Plugin\Event\Listener\ProductPackagingUnitUnpublishStorageListener;

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
        $eventCollection->addListenerQueued(ProductPackagingUnitEvents::PRODUCT_PACKAGING_UNIT_PUBLISH, new ProductPackagingUnitPublishStorageListener(), 0, null, $this->getConfig()->getEventQueueName());

        $eventCollection->addListenerQueued(ProductPackagingUnitEvents::ENTITY_SPY_PRODUCT_PACKAGING_UNIT_TYPE_CREATE, new ProductPackagingUnitTypePublishStorageListener(), 0, null, $this->getConfig()->getEventQueueName());
        $eventCollection->addListenerQueued(ProductPackagingUnitEvents::ENTITY_SPY_PRODUCT_PACKAGING_UNIT_TYPE_UPDATE, new ProductPackagingUnitTypePublishStorageListener(), 0, null, $this->getConfig()->getEventQueueName());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductConcreteUnpublishStorageListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(ProductPackagingUnitEvents::PRODUCT_PACKAGING_UNIT_UNPUBLISH, new ProductPackagingUnitUnpublishStorageListener(), 0, null, $this->getConfig()->getEventQueueName());

        $eventCollection->addListenerQueued(ProductPackagingUnitEvents::ENTITY_SPY_PRODUCT_PACKAGING_UNIT_TYPE_DELETE, new ProductPackagingUnitTypeUnpublishStorageListener(), 0, null, $this->getConfig()->getEventQueueName());
    }
}
