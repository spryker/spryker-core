<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipStorage\Communication\Plugin\Event\Subscriber;

use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PriceProductMerchantRelationship\Dependency\PriceProductMerchantRelationshipEvents;
use Spryker\Zed\PriceProductMerchantRelationshipStorage\Communication\Plugin\Event\Listener\PriceProductMerchantRelationshipAbstractListener;
use Spryker\Zed\PriceProductMerchantRelationshipStorage\Communication\Plugin\Event\Listener\PriceProductMerchantRelationshipConcreteListener;
use Spryker\Zed\PriceProductMerchantRelationshipStorage\Communication\Plugin\Event\Listener\PriceProductStoreAbstractListener;
use Spryker\Zed\PriceProductMerchantRelationshipStorage\Communication\Plugin\Event\Listener\PriceProductStoreConcreteListener;

/**
 * @method \Spryker\Zed\PriceProductMerchantRelationshipStorage\Business\PriceProductMerchantRelationshipStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\PriceProductMerchantRelationshipStorage\Communication\PriceProductMerchantRelationshipStorageCommunicationFactory getFactory()
 */
class PriceProductMerchantRelationshipStorageEventSubscriber extends AbstractPlugin implements EventSubscriberInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return \Spryker\Zed\Event\Dependency\EventCollectionInterface
     */
    public function getSubscribedEvents(EventCollectionInterface $eventCollection): EventCollectionInterface
    {
        $this->addConcretePriceProductStoreCreateListener($eventCollection)
            ->addConcretePriceProductStoreUpdateListener($eventCollection)
            ->addConcretePriceProductStoreDeleteListener($eventCollection)
            ->addConcretePriceProductBusinessUnitCreateListener($eventCollection)
            ->addConcretePriceProductBusinessUnitUpdateListener($eventCollection)
            ->addConcretePriceProductBusinessUnitDeleteListener($eventCollection)
            ->addAbstractPriceProductStoreCreateListener($eventCollection)
            ->addAbstractPriceProductStoreUpdateListener($eventCollection)
            ->addAbstractPriceProductStoreDeleteListener($eventCollection)
            ->addAbstractPriceProductBusinessUnitCreateListener($eventCollection)
            ->addAbstractPriceProductBusinessUnitUpdateListener($eventCollection)
            ->addAbstractPriceProductBusinessUnitDeleteListener($eventCollection);

        return $eventCollection;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return $this
     */
    protected function addConcretePriceProductStoreCreateListener(EventCollectionInterface $eventCollection): self
    {
        $eventCollection->addListenerQueued(
            PriceProductMerchantRelationshipEvents::ENTITY_SPY_PRICE_PRODUCT_STORE_CREATE,
            new PriceProductStoreConcreteListener()
        );

        return $this;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return $this
     */
    protected function addConcretePriceProductStoreUpdateListener(EventCollectionInterface $eventCollection): self
    {
        $eventCollection->addListenerQueued(
            PriceProductMerchantRelationshipEvents::ENTITY_SPY_PRICE_PRODUCT_STORE_UPDATE,
            new PriceProductStoreConcreteListener()
        );

        return $this;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return $this
     */
    protected function addConcretePriceProductStoreDeleteListener(EventCollectionInterface $eventCollection): self
    {
        $eventCollection->addListenerQueued(
            PriceProductMerchantRelationshipEvents::ENTITY_SPY_PRICE_PRODUCT_STORE_DELETE,
            new PriceProductStoreConcreteListener()
        );

        return $this;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return $this
     */
    protected function addConcretePriceProductBusinessUnitCreateListener(EventCollectionInterface $eventCollection): self
    {
        $eventCollection->addListenerQueued(
            PriceProductMerchantRelationshipEvents::ENTITY_SPY_PRICE_PRODUCT_MERCHANT_RELATION_CREATE,
            new PriceProductMerchantRelationshipConcreteListener()
        );

        return $this;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return $this
     */
    protected function addConcretePriceProductBusinessUnitUpdateListener(EventCollectionInterface $eventCollection): self
    {
        $eventCollection->addListenerQueued(
            PriceProductMerchantRelationshipEvents::ENTITY_SPY_PRICE_PRODUCT_MERCHANT_RELATION_UPDATE,
            new PriceProductMerchantRelationshipConcreteListener()
        );

        return $this;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return $this
     */
    protected function addConcretePriceProductBusinessUnitDeleteListener(EventCollectionInterface $eventCollection): self
    {
        $eventCollection->addListenerQueued(
            PriceProductMerchantRelationshipEvents::ENTITY_SPY_PRICE_PRODUCT_MERCHANT_RELATION_DELETE,
            new PriceProductMerchantRelationshipConcreteListener()
        );

        return $this;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return $this
     */
    protected function addAbstractPriceProductStoreCreateListener(EventCollectionInterface $eventCollection): self
    {
        $eventCollection->addListenerQueued(
            PriceProductMerchantRelationshipEvents::ENTITY_SPY_PRICE_PRODUCT_STORE_CREATE,
            new PriceProductStoreAbstractListener()
        );

        return $this;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return $this
     */
    protected function addAbstractPriceProductStoreUpdateListener(EventCollectionInterface $eventCollection): self
    {
        $eventCollection->addListenerQueued(
            PriceProductMerchantRelationshipEvents::ENTITY_SPY_PRICE_PRODUCT_STORE_UPDATE,
            new PriceProductStoreAbstractListener()
        );

        return $this;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return $this
     */
    protected function addAbstractPriceProductStoreDeleteListener(EventCollectionInterface $eventCollection): self
    {
        $eventCollection->addListenerQueued(
            PriceProductMerchantRelationshipEvents::ENTITY_SPY_PRICE_PRODUCT_STORE_DELETE,
            new PriceProductStoreAbstractListener()
        );

        return $this;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return $this
     */
    protected function addAbstractPriceProductBusinessUnitCreateListener(EventCollectionInterface $eventCollection): self
    {
        $eventCollection->addListenerQueued(
            PriceProductMerchantRelationshipEvents::ENTITY_SPY_PRICE_PRODUCT_MERCHANT_RELATION_CREATE,
            new PriceProductMerchantRelationshipAbstractListener()
        );

        return $this;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return $this
     */
    protected function addAbstractPriceProductBusinessUnitUpdateListener(EventCollectionInterface $eventCollection): self
    {
        $eventCollection->addListenerQueued(
            PriceProductMerchantRelationshipEvents::ENTITY_SPY_PRICE_PRODUCT_MERCHANT_RELATION_UPDATE,
            new PriceProductMerchantRelationshipAbstractListener()
        );

        return $this;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return $this
     */
    protected function addAbstractPriceProductBusinessUnitDeleteListener(EventCollectionInterface $eventCollection): self
    {
        $eventCollection->addListenerQueued(
            PriceProductMerchantRelationshipEvents::ENTITY_SPY_PRICE_PRODUCT_MERCHANT_RELATION_DELETE,
            new PriceProductMerchantRelationshipAbstractListener()
        );

        return $this;
    }
}
