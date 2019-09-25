<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipStorage\Communication\Plugin\Event\Subscriber;

use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MerchantRelationship\Dependency\MerchantRelationshipEvents;
use Spryker\Zed\PriceProductMerchantRelationship\Dependency\PriceProductMerchantRelationshipEvents;
use Spryker\Zed\PriceProductMerchantRelationshipStorage\Communication\Plugin\Event\Listener\MerchantRelationshipListener;
use Spryker\Zed\PriceProductMerchantRelationshipStorage\Communication\Plugin\Event\Listener\PriceProductMerchantRelationshipAbstractDeleteListener;
use Spryker\Zed\PriceProductMerchantRelationshipStorage\Communication\Plugin\Event\Listener\PriceProductMerchantRelationshipAbstractListener;
use Spryker\Zed\PriceProductMerchantRelationshipStorage\Communication\Plugin\Event\Listener\PriceProductMerchantRelationshipConcreteDeleteListener;
use Spryker\Zed\PriceProductMerchantRelationshipStorage\Communication\Plugin\Event\Listener\PriceProductMerchantRelationshipConcreteListener;

/**
 * @method \Spryker\Zed\PriceProductMerchantRelationshipStorage\Business\PriceProductMerchantRelationshipStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\PriceProductMerchantRelationshipStorage\Communication\PriceProductMerchantRelationshipStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\PriceProductMerchantRelationshipStorage\PriceProductMerchantRelationshipStorageConfig getConfig()
 */
class PriceProductMerchantRelationshipStorageEventSubscriber extends AbstractPlugin implements EventSubscriberInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return \Spryker\Zed\Event\Dependency\EventCollectionInterface
     */
    public function getSubscribedEvents(EventCollectionInterface $eventCollection): EventCollectionInterface
    {
        $this
            ->addMerchantRelationshipCreateListener($eventCollection)
            ->addMerchantRelationshipUpdateListener($eventCollection)
            ->addMerchantRelationshipDeleteListener($eventCollection)
            ->addConcretePriceProductMerchantRelationshipCreateListener($eventCollection)
            ->addConcretePriceProductMerchantRelationshipUpdateListener($eventCollection)
            ->addConcretePriceProductMerchantRelationshipDeleteListener($eventCollection)
            ->addAbstractPriceProductMerchantRelationshipCreateListener($eventCollection)
            ->addAbstractPriceProductMerchantRelationshipUpdateListener($eventCollection)
            ->addAbstractPriceProductMerchantRelationshipDeleteListener($eventCollection);

        return $eventCollection;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return $this
     */
    protected function addMerchantRelationshipCreateListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(
            MerchantRelationshipEvents::ENTITY_SPY_MERCHANT_RELATIONSHIP_TO_COMPANY_BUSINESS_UNIT_CREATE,
            new MerchantRelationshipListener()
        );

        return $this;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return $this
     */
    protected function addMerchantRelationshipUpdateListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(
            MerchantRelationshipEvents::ENTITY_SPY_MERCHANT_RELATIONSHIP_TO_COMPANY_BUSINESS_UNIT_UPDATE,
            new MerchantRelationshipListener()
        );

        return $this;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return $this
     */
    protected function addMerchantRelationshipDeleteListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(
            MerchantRelationshipEvents::ENTITY_SPY_MERCHANT_RELATIONSHIP_TO_COMPANY_BUSINESS_UNIT_DELETE,
            new MerchantRelationshipListener()
        );

        return $this;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return $this
     */
    protected function addConcretePriceProductMerchantRelationshipCreateListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(
            PriceProductMerchantRelationshipEvents::ENTITY_SPY_PRICE_PRODUCT_MERCHANT_RELATIONSHIP_CREATE,
            new PriceProductMerchantRelationshipConcreteListener()
        );

        return $this;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return $this
     */
    protected function addConcretePriceProductMerchantRelationshipUpdateListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(
            PriceProductMerchantRelationshipEvents::ENTITY_SPY_PRICE_PRODUCT_MERCHANT_RELATIONSHIP_UPDATE,
            new PriceProductMerchantRelationshipConcreteListener()
        );

        return $this;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return $this
     */
    protected function addConcretePriceProductMerchantRelationshipDeleteListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(
            PriceProductMerchantRelationshipEvents::ENTITY_SPY_PRICE_PRODUCT_MERCHANT_RELATIONSHIP_DELETE,
            new PriceProductMerchantRelationshipConcreteDeleteListener()
        );

        return $this;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return $this
     */
    protected function addAbstractPriceProductMerchantRelationshipCreateListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(
            PriceProductMerchantRelationshipEvents::ENTITY_SPY_PRICE_PRODUCT_MERCHANT_RELATIONSHIP_CREATE,
            new PriceProductMerchantRelationshipAbstractListener()
        );

        return $this;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return $this
     */
    protected function addAbstractPriceProductMerchantRelationshipUpdateListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(
            PriceProductMerchantRelationshipEvents::ENTITY_SPY_PRICE_PRODUCT_MERCHANT_RELATIONSHIP_UPDATE,
            new PriceProductMerchantRelationshipAbstractListener()
        );

        return $this;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return $this
     */
    protected function addAbstractPriceProductMerchantRelationshipDeleteListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(
            PriceProductMerchantRelationshipEvents::ENTITY_SPY_PRICE_PRODUCT_MERCHANT_RELATIONSHIP_DELETE,
            new PriceProductMerchantRelationshipAbstractDeleteListener()
        );

        return $this;
    }
}
