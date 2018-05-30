<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductGroupStorage\Communication\Plugin\Event\Subscriber;

use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductGroup\Dependency\ProductGroupEvents;
use Spryker\Zed\ProductGroupStorage\Communication\Plugin\Event\Listener\ProductAbstractGroupPublishStorageListener;
use Spryker\Zed\ProductGroupStorage\Communication\Plugin\Event\Listener\ProductAbstractGroupStorageListener;

/**
 * @method \Spryker\Zed\ProductGroupStorage\Communication\ProductGroupStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductGroupStorage\Business\ProductGroupStorageFacadeInterface getFacade()
 */
class ProductGroupStorageEventSubscriber extends AbstractPlugin implements EventSubscriberInterface
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
        $this->addProductAbstractGroupPublishStorageListener($eventCollection);
        $this->addProductAbstractGroupUnpublishStorageListener($eventCollection);
        $this->addProductAbstractGroupCreateStorageListener($eventCollection);
        $this->addProductAbstractGroupUpdateStorageListener($eventCollection);
        $this->addProductAbstractGroupDeleteStorageListener($eventCollection);

        return $eventCollection;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductAbstractGroupPublishStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductGroupEvents::PRODUCT_GROUP_PUBLISH, new ProductAbstractGroupPublishStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductAbstractGroupUnpublishStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductGroupEvents::PRODUCT_GROUP_UNPUBLISH, new ProductAbstractGroupPublishStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductAbstractGroupCreateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductGroupEvents::ENTITY_SPY_PRODUCT_ABSTRACT_GROUP_CREATE, new ProductAbstractGroupStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductAbstractGroupUpdateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductGroupEvents::ENTITY_SPY_PRODUCT_ABSTRACT_GROUP_UPDATE, new ProductAbstractGroupStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductAbstractGroupDeleteStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductGroupEvents::ENTITY_SPY_PRODUCT_ABSTRACT_GROUP_DELETE, new ProductAbstractGroupStorageListener());
    }
}
