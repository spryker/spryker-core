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
use Spryker\Zed\ProductOptionStorage\Communication\Plugin\Event\Listener\ProductOptionValuePriceStorageListener;
use Spryker\Zed\ProductOptionStorage\Communication\Plugin\Event\Listener\ProductOptionValueStorageListener;

/**
 * @method \Spryker\Zed\ProductOptionStorage\Communication\ProductOptionStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductOptionStorage\Business\ProductOptionStorageFacadeInterface getFacade()
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
        $this->addProductOptionPublishStorageListener($eventCollection);
        $this->addProductOptionUnpublishStorageListener($eventCollection);
        $this->addProductOptionCreateStorageListener($eventCollection);
        $this->addProductOptionUpdateStorageListener($eventCollection);
        $this->addProductOptionDeleteStorageListener($eventCollection);
        $this->addProductOptionGroupUpdateStorageListener($eventCollection);
        $this->addProductOptionGroupDeleteStorageListener($eventCollection);
        $this->addProductOptionValueCreateStorageListener($eventCollection);
        $this->addProductOptionValueUpdateStorageListener($eventCollection);
        $this->addProductOptionValueDeleteStorageListener($eventCollection);
        $this->addProductOptionValuePriceCreateStorageListener($eventCollection);
        $this->addProductOptionValuePriceUpdateStorageListener($eventCollection);
        $this->addProductOptionValuePriceDeleteStorageListener($eventCollection);

        return $eventCollection;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductOptionPublishStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductOptionEvents::PRODUCT_ABSTRACT_PRODUCT_OPTION_PUBLISH, new ProductOptionPublishStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductOptionUnpublishStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductOptionEvents::PRODUCT_ABSTRACT_PRODUCT_OPTION_UNPUBLISH, new ProductOptionPublishStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductOptionCreateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductOptionEvents::ENTITY_SPY_PRODUCT_ABSTRACT_PRODUCT_OPTION_GROUP_CREATE, new ProductOptionStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductOptionUpdateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductOptionEvents::ENTITY_SPY_PRODUCT_ABSTRACT_PRODUCT_OPTION_GROUP_UPDATE, new ProductOptionStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductOptionDeleteStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductOptionEvents::ENTITY_SPY_PRODUCT_ABSTRACT_PRODUCT_OPTION_GROUP_DELETE, new ProductOptionStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductOptionGroupUpdateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductOptionEvents::ENTITY_SPY_PRODUCT_OPTION_GROUP_UPDATE, new ProductOptionGroupStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductOptionGroupDeleteStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductOptionEvents::ENTITY_SPY_PRODUCT_OPTION_GROUP_DELETE, new ProductOptionGroupStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductOptionValueCreateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductOptionEvents::ENTITY_SPY_PRODUCT_OPTION_VALUE_CREATE, new ProductOptionValueStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductOptionValueUpdateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductOptionEvents::ENTITY_SPY_PRODUCT_OPTION_VALUE_UPDATE, new ProductOptionValueStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductOptionValueDeleteStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductOptionEvents::ENTITY_SPY_PRODUCT_OPTION_VALUE_DELETE, new ProductOptionValueStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductOptionValuePriceCreateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductOptionEvents::ENTITY_SPY_PRODUCT_OPTION_VALUE_PRICE_CREATE, new ProductOptionValuePriceStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductOptionValuePriceUpdateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductOptionEvents::ENTITY_SPY_PRODUCT_OPTION_VALUE_PRICE_UPDATE, new ProductOptionValuePriceStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductOptionValuePriceDeleteStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductOptionEvents::ENTITY_SPY_PRODUCT_OPTION_VALUE_PRICE_DELETE, new ProductOptionValuePriceStorageListener());
    }
}
