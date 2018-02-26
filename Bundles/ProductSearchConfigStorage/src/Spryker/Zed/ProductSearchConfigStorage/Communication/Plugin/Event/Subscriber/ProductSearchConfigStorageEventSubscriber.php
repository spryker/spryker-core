<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearchConfigStorage\Communication\Plugin\Event\Subscriber;

use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Product\Dependency\ProductEvents;
use Spryker\Zed\ProductSearch\Dependency\ProductSearchEvents;
use Spryker\Zed\ProductSearchConfigStorage\Communication\Plugin\Event\Listener\ProductSearchConfigStorageListener;

/**
 * @method \Spryker\Zed\ProductSearchConfigStorage\Communication\ProductSearchConfigStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductSearchConfigStorage\Business\ProductSearchConfigStorageFacadeInterface getFacade()
 */
class ProductSearchConfigStorageEventSubscriber extends AbstractPlugin implements EventSubscriberInterface
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
        $this->addProductSearchConfigPublishStorageListener($eventCollection);
        $this->addProductSearchConfigUnpublishStorageListener($eventCollection);
        $this->addProductSearchConfigCreateStorageListener($eventCollection);
        $this->addProductSearchConfigUpdateStorageListener($eventCollection);
        $this->addProductSearchConfigDeleteStorageListener($eventCollection);
        $this->addProductSearchConfigKeyCreateStorageListener($eventCollection);
        $this->addProductSearchConfigKeyUpdateStorageListener($eventCollection);
        $this->addProductSearchConfigKeyDeleteStorageListener($eventCollection);

        return $eventCollection;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductSearchConfigPublishStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductSearchEvents::PRODUCT_SEARCH_CONFIG_PUBLISH, new ProductSearchConfigStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductSearchConfigUnpublishStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductSearchEvents::PRODUCT_SEARCH_CONFIG_UNPUBLISH, new ProductSearchConfigStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductSearchConfigCreateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductSearchEvents::ENTITY_SPY_PRODUCT_SEARCH_ATTRIBUTE_CREATE, new ProductSearchConfigStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductSearchConfigUpdateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductSearchEvents::ENTITY_SPY_PRODUCT_SEARCH_ATTRIBUTE_UPDATE, new ProductSearchConfigStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductSearchConfigDeleteStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductSearchEvents::ENTITY_SPY_PRODUCT_SEARCH_ATTRIBUTE_DELETE, new ProductSearchConfigStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductSearchConfigKeyCreateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_ATTRIBUTE_KEY_CREATE, new ProductSearchConfigStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductSearchConfigKeyUpdateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_ATTRIBUTE_KEY_UPDATE, new ProductSearchConfigStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductSearchConfigKeyDeleteStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_ATTRIBUTE_KEY_DELETE, new ProductSearchConfigStorageListener());
    }
}
