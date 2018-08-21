<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductResourceAliasStorage\Communication\Plugin\Event\Subscriber;

use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PriceProduct\Dependency\PriceProductEvents;
use Spryker\Zed\PriceProductResourceAliasStorage\Communication\Plugin\Event\Listener\PriceProductAbstractMappingResourceStorageListener;
use Spryker\Zed\PriceProductResourceAliasStorage\Communication\Plugin\Event\Listener\PriceProductConcreteMappingResourceStorageListener;

/**
 * @method \Spryker\Zed\PriceProductResourceAliasStorage\Business\PriceProductResourceAliasStorageFacade getFacade()
 * @method \Spryker\Zed\PriceProductResourceAliasStorage\Communication\PriceProductResourceAliasStorageCommunicationFactory getFactory()
 */
class PriceProductMappingResourceStorageEventSubscriber extends AbstractPlugin implements EventSubscriberInterface
{
    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return \Spryker\Zed\Event\Dependency\EventCollectionInterface
     */
    public function getSubscribedEvents(EventCollectionInterface $eventCollection): EventCollectionInterface
    {
        $this->addPriceProductAbstractMappingResourceStorageListener($eventCollection);
        $this->addPriceProductConcreteMappingResourceStorageListener($eventCollection);

        return $eventCollection;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addPriceProductAbstractMappingResourceStorageListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(
            PriceProductEvents::PRICE_ABSTRACT_PUBLISH,
            new PriceProductAbstractMappingResourceStorageListener()
        );
        $eventCollection->addListenerQueued(
            PriceProductEvents::ENTITY_SPY_PRICE_PRODUCT_CREATE,
            new PriceProductAbstractMappingResourceStorageListener()
        );
        $eventCollection->addListenerQueued(
            PriceProductEvents::ENTITY_SPY_PRICE_PRODUCT_STORE_CREATE,
            new PriceProductAbstractMappingResourceStorageListener()
        );
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addPriceProductConcreteMappingResourceStorageListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(
            PriceProductEvents::PRICE_CONCRETE_PUBLISH,
            new PriceProductConcreteMappingResourceStorageListener()
        );
        $eventCollection->addListenerQueued(
            PriceProductEvents::ENTITY_SPY_PRICE_PRODUCT_CREATE,
            new PriceProductConcreteMappingResourceStorageListener()
        );
        $eventCollection->addListenerQueued(
            PriceProductEvents::ENTITY_SPY_PRICE_PRODUCT_STORE_CREATE,
            new PriceProductConcreteMappingResourceStorageListener()
        );
    }
}
