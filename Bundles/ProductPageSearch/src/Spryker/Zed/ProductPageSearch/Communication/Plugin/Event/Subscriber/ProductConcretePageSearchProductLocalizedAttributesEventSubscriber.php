<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Subscriber;

use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Product\Dependency\ProductEvents;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductConcretePageSearchProductLocalizedAttributesListener;

/**
 * @method \Spryker\Zed\ProductPageSearch\Business\ProductPageSearchFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductPageSearch\Communication\ProductPageSearchCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductPageSearch\ProductPageSearchConfig getConfig()
 * @method \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchQueryContainerInterface getQueryContainer()
 */
class ProductConcretePageSearchProductLocalizedAttributesEventSubscriber extends AbstractPlugin implements EventSubscriberInterface
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
        $this->addProductConcretePageProductConcreteLocalizedAttributesCreateSearchListener($eventCollection);
        $this->addProductConcretePageProductConcreteLocalizedAttributesUpdateSearchListener($eventCollection);
        $this->addProductConcretePageProductConcreteLocalizedAttributesDeleteSearchListener($eventCollection);

        return $eventCollection;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductConcretePageProductConcreteLocalizedAttributesCreateSearchListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_LOCALIZED_ATTRIBUTES_CREATE, new ProductConcretePageSearchProductLocalizedAttributesListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductConcretePageProductConcreteLocalizedAttributesUpdateSearchListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_LOCALIZED_ATTRIBUTES_UPDATE, new ProductConcretePageSearchProductLocalizedAttributesListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductConcretePageProductConcreteLocalizedAttributesDeleteSearchListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_LOCALIZED_ATTRIBUTES_DELETE, new ProductConcretePageSearchProductLocalizedAttributesListener());
    }
}
