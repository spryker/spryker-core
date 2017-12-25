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
        $eventCollection
            ->addListenerQueued(ProductRelationEvents::PRODUCT_ABSTRACT_RELATION_PUBLISH, new ProductRelationPublishStorageListener())
            ->addListenerQueued(ProductRelationEvents::PRODUCT_ABSTRACT_RELATION_UNPUBLISH, new ProductRelationPublishStorageListener())
            ->addListenerQueued(ProductRelationEvents::ENTITY_SPY_PRODUCT_RELATION_CREATE, new ProductRelationStorageListener())
            ->addListenerQueued(ProductRelationEvents::ENTITY_SPY_PRODUCT_RELATION_UPDATE, new ProductRelationStorageListener())
            ->addListenerQueued(ProductRelationEvents::ENTITY_SPY_PRODUCT_RELATION_DELETE, new ProductRelationStorageListener())
            ->addListenerQueued(ProductRelationEvents::ENTITY_SPY_PRODUCT_RELATION_PRODUCT_ABSTRACT_CREATE, new ProductRelationProductAbstractStorageListener())
            ->addListenerQueued(ProductRelationEvents::ENTITY_SPY_PRODUCT_RELATION_PRODUCT_ABSTRACT_UPDATE, new ProductRelationProductAbstractStorageListener())
            ->addListenerQueued(ProductRelationEvents::ENTITY_SPY_PRODUCT_RELATION_PRODUCT_ABSTRACT_DELETE, new ProductRelationProductAbstractStorageListener());

        return $eventCollection;
    }

}
