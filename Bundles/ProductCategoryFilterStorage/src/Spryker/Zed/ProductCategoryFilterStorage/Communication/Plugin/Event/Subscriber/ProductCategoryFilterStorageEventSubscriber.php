<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryFilterStorage\Communication\Plugin\Event\Subscriber;

use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductCategoryFilter\Dependency\ProductCategoryFilterEvents;
use Spryker\Zed\ProductCategoryFilterStorage\Communication\Plugin\Event\Listener\ProductCategoryFilterPublishStorageListener;

/**
 * @method \Spryker\Zed\ProductCategoryFilterStorage\Communication\ProductCategoryFilterStorageCommunicationFactory getFactory()
 */
class ProductCategoryFilterStorageEventSubscriber extends AbstractPlugin implements EventSubscriberInterface
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
            ->addListenerQueued(ProductCategoryFilterEvents::ENTITY_SPY_PRODUCT_CATEGORY_FILTER_CREATE, new ProductCategoryFilterPublishStorageListener())
            ->addListenerQueued(ProductCategoryFilterEvents::ENTITY_SPY_PRODUCT_CATEGORY_FILTER_UPDATE, new ProductCategoryFilterPublishStorageListener())
            ->addListenerQueued(ProductCategoryFilterEvents::ENTITY_SPY_PRODUCT_CATEGORY_FILTER_DELETE, new ProductCategoryFilterPublishStorageListener())
            ;

        return $eventCollection;
    }

}
