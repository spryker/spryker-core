<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxStorage\Communication\Plugin\Event\Subscriber;

use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Tax\Dependency\TaxEvents;
use Spryker\Zed\TaxStorage\Communication\Plugin\Event\Listener\TaxRateStoragePublishListener;
use Spryker\Zed\TaxStorage\Communication\Plugin\Event\Listener\TaxSetStoragePublishListener;
use Spryker\Zed\TaxStorage\Communication\Plugin\Event\Listener\TaxSetStorageUnpublishListener;
use Spryker\Zed\TaxStorage\Communication\Plugin\Event\Listener\TaxSetTaxStoragePublishListener;

/**
 * @method \Spryker\Zed\TaxStorage\Communication\TaxStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\TaxStorage\Business\TaxStorageFacade getFacade()
 * @method \Spryker\Zed\TaxStorage\TaxStorageConfig getConfig()
 */
class TaxStorageSubscriber extends AbstractPlugin implements EventSubscriberInterface
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
    public function getSubscribedEvents(EventCollectionInterface $eventCollection)
    {
        $this
            ->addTaxSetCreateStorageListener($eventCollection)
            ->addTaxSetUpdateStorageListener($eventCollection)
            ->addTaxSetDeleteStorageListener($eventCollection)
            ->addTaxRateUpdateStorageListener($eventCollection)
            ->addTaxRateDeleteStorageListener($eventCollection)
            ->addTaxSetTaxUpdateStorageListener($eventCollection)
            ->addTaxSetTaxDeleteStorageListener($eventCollection);

        return $eventCollection;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return $this
     */
    protected function addTaxSetCreateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(TaxEvents::ENTITY_SPY_TAX_SET_CREATE, new TaxSetStoragePublishListener());

        return $this;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return $this
     */
    protected function addTaxSetUpdateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(TaxEvents::ENTITY_SPY_TAX_SET_UPDATE, new TaxSetStoragePublishListener());

        return $this;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return $this
     */
    protected function addTaxSetDeleteStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(TaxEvents::ENTITY_SPY_TAX_SET_DELETE, new TaxSetStorageUnpublishListener());

        return $this;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return $this
     */
    protected function addTaxRateUpdateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(TaxEvents::ENTITY_SPY_TAX_RATE_UPDATE, new TaxRateStoragePublishListener());

        return $this;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return $this
     */
    protected function addTaxRateDeleteStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(TaxEvents::ENTITY_SPY_TAX_RATE_DELETE, new TaxRateStoragePublishListener());

        return $this;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return $this
     */
    protected function addTaxSetTaxUpdateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(TaxEvents::ENTITY_SPY_TAX_SET_TAX_CREATE, new TaxSetTaxStoragePublishListener());

        return $this;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return $this
     */
    protected function addTaxSetTaxDeleteStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(TaxEvents::ENTITY_SPY_TAX_SET_TAX_DELETE, new TaxSetTaxStoragePublishListener());

        return $this;
    }
}
