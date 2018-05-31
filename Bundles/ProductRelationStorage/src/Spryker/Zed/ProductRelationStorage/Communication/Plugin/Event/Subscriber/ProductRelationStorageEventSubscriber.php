<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelationStorage\Communication\Plugin\Event\Subscriber;

use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductRelation\Dependency\ProductRelationEvents;
use Spryker\Zed\ProductRelationStorage\Communication\Plugin\Event\Listener\ProductRelationProductAbstractStorageListener;
use Spryker\Zed\ProductRelationStorage\Communication\Plugin\Event\Listener\ProductRelationPublishStorageListener;
use Spryker\Zed\ProductRelationStorage\Communication\Plugin\Event\Listener\ProductRelationStorageListener;

/**
 * @method \Spryker\Zed\ProductRelationStorage\Communication\ProductRelationStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductRelationStorage\Business\ProductRelationStorageFacadeInterface getFacade()
 */
class ProductRelationStorageEventSubscriber extends AbstractPlugin implements EventSubscriberInterface
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
        $this->addProductRelationPublishStorageListener($eventCollection);
        $this->addProductRelationUnpublishStorageListener($eventCollection);
        $this->addProductRelationCreateStorageListener($eventCollection);
        $this->addProductRelationUpdateStorageListener($eventCollection);
        $this->addProductRelationDeleteStorageListener($eventCollection);
        $this->addProductRelationProductAbstractCreateStorageListener($eventCollection);
        $this->addProductRelationProductAbstractUpdateStorageListener($eventCollection);
        $this->addProductRelationProductAbstractDeleteStorageListener($eventCollection);

        return $eventCollection;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductRelationPublishStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductRelationEvents::PRODUCT_ABSTRACT_RELATION_PUBLISH, new ProductRelationPublishStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductRelationUnpublishStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductRelationEvents::PRODUCT_ABSTRACT_RELATION_UNPUBLISH, new ProductRelationPublishStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductRelationCreateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductRelationEvents::ENTITY_SPY_PRODUCT_RELATION_CREATE, new ProductRelationStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductRelationUpdateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductRelationEvents::ENTITY_SPY_PRODUCT_RELATION_UPDATE, new ProductRelationStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductRelationDeleteStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductRelationEvents::ENTITY_SPY_PRODUCT_RELATION_DELETE, new ProductRelationStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductRelationProductAbstractCreateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductRelationEvents::ENTITY_SPY_PRODUCT_RELATION_PRODUCT_ABSTRACT_CREATE, new ProductRelationProductAbstractStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductRelationProductAbstractUpdateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductRelationEvents::ENTITY_SPY_PRODUCT_RELATION_PRODUCT_ABSTRACT_UPDATE, new ProductRelationProductAbstractStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductRelationProductAbstractDeleteStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductRelationEvents::ENTITY_SPY_PRODUCT_RELATION_PRODUCT_ABSTRACT_DELETE, new ProductRelationProductAbstractStorageListener());
    }
}
