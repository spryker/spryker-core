<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOptionStorage\Communication\Plugin\Event\Subscriber;

use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductOption\Dependency\ProductOptionEvents;
use Spryker\Zed\ProductOptionStorage\Communication\Plugin\Event\Listener\ProductOptionGroupStorageListener;
use Spryker\Zed\ProductOptionStorage\Communication\Plugin\Event\Listener\ProductOptionPublishStorageListener;
use Spryker\Zed\ProductOptionStorage\Communication\Plugin\Event\Listener\ProductOptionStorageListener;
use Spryker\Zed\ProductOptionStorage\Communication\Plugin\Event\Listener\ProductOptionValueStorageListener;

/**
 * @method \Spryker\Zed\ProductOptionStorage\Communication\ProductOptionStorageCommunicationFactory getFactory()
 */
class ProductOptionStorageEventSubscriber extends AbstractPlugin implements EventSubscriberInterface
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
            ->addListenerQueued(ProductOptionEvents::ENTITY_SPY_PRODUCT_ABSTRACT_PRODUCT_OPTION_GROUP_CREATE, new ProductOptionStorageListener())
            ->addListenerQueued(ProductOptionEvents::ENTITY_SPY_PRODUCT_ABSTRACT_PRODUCT_OPTION_GROUP_UPDATE, new ProductOptionStorageListener())
            ->addListenerQueued(ProductOptionEvents::ENTITY_SPY_PRODUCT_ABSTRACT_PRODUCT_OPTION_GROUP_DELETE, new ProductOptionStorageListener())
            ->addListenerQueued(ProductOptionEvents::ENTITY_SPY_PRODUCT_OPTION_GROUP_UPDATE, new ProductOptionGroupStorageListener())
            ->addListenerQueued(ProductOptionEvents::ENTITY_SPY_PRODUCT_OPTION_GROUP_DELETE, new ProductOptionGroupStorageListener())
            ->addListenerQueued(ProductOptionEvents::ENTITY_SPY_PRODUCT_OPTION_VALUE_CREATE, new ProductOptionValueStorageListener())
            ->addListenerQueued(ProductOptionEvents::ENTITY_SPY_PRODUCT_OPTION_VALUE_UPDATE, new ProductOptionValueStorageListener())
            ->addListenerQueued(ProductOptionEvents::ENTITY_SPY_PRODUCT_OPTION_VALUE_DELETE, new ProductOptionValueStorageListener());

        return $eventCollection;
    }

}
