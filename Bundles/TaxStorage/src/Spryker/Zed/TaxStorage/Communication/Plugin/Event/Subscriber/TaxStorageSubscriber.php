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
 * @method \Spryker\Zed\TaxStorage\Business\TaxStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\TaxStorage\TaxStorageConfig getConfig()
 */
class TaxStorageSubscriber extends AbstractPlugin implements EventSubscriberInterface
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
        $eventCollection = $this->addTaxSetPublishStorageListener($eventCollection);
        $eventCollection = $this->addTaxSetCreateStorageListener($eventCollection);
        $eventCollection = $this->addTaxSetUpdateStorageListener($eventCollection);
        $eventCollection = $this->addTaxSetDeleteStorageListener($eventCollection);
        $eventCollection = $this->addTaxRateUpdateStorageListener($eventCollection);
        $eventCollection = $this->addTaxRateDeleteStorageListener($eventCollection);
        $eventCollection = $this->addTaxSetTaxUpdateStorageListener($eventCollection);
        $eventCollection = $this->addTaxSetTaxDeleteStorageListener($eventCollection);

        return $eventCollection;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return \Spryker\Zed\Event\Dependency\EventCollectionInterface
     */
    protected function addTaxSetPublishStorageListener(EventCollectionInterface $eventCollection): EventCollectionInterface
    {
        return $eventCollection->addListenerQueued(TaxEvents::TAX_SET_PUBLISH, new TaxSetStoragePublishListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return \Spryker\Zed\Event\Dependency\EventCollectionInterface
     */
    protected function addTaxSetCreateStorageListener(EventCollectionInterface $eventCollection): EventCollectionInterface
    {
        return $eventCollection->addListenerQueued(TaxEvents::ENTITY_SPY_TAX_SET_CREATE, new TaxSetStoragePublishListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return \Spryker\Zed\Event\Dependency\EventCollectionInterface
     */
    protected function addTaxSetUpdateStorageListener(EventCollectionInterface $eventCollection): EventCollectionInterface
    {
        return $eventCollection->addListenerQueued(TaxEvents::ENTITY_SPY_TAX_SET_UPDATE, new TaxSetStoragePublishListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return \Spryker\Zed\Event\Dependency\EventCollectionInterface
     */
    protected function addTaxSetDeleteStorageListener(EventCollectionInterface $eventCollection): EventCollectionInterface
    {
        return $eventCollection->addListenerQueued(TaxEvents::ENTITY_SPY_TAX_SET_DELETE, new TaxSetStorageUnpublishListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return \Spryker\Zed\Event\Dependency\EventCollectionInterface
     */
    protected function addTaxRateUpdateStorageListener(EventCollectionInterface $eventCollection): EventCollectionInterface
    {
        return $eventCollection->addListenerQueued(TaxEvents::ENTITY_SPY_TAX_RATE_UPDATE, new TaxRateStoragePublishListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return \Spryker\Zed\Event\Dependency\EventCollectionInterface
     */
    protected function addTaxRateDeleteStorageListener(EventCollectionInterface $eventCollection): EventCollectionInterface
    {
        return $eventCollection->addListenerQueued(TaxEvents::ENTITY_SPY_TAX_RATE_DELETE, new TaxRateStoragePublishListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return \Spryker\Zed\Event\Dependency\EventCollectionInterface
     */
    protected function addTaxSetTaxUpdateStorageListener(EventCollectionInterface $eventCollection): EventCollectionInterface
    {
        return $eventCollection->addListenerQueued(TaxEvents::ENTITY_SPY_TAX_SET_TAX_CREATE, new TaxSetTaxStoragePublishListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return \Spryker\Zed\Event\Dependency\EventCollectionInterface
     */
    protected function addTaxSetTaxDeleteStorageListener(EventCollectionInterface $eventCollection): EventCollectionInterface
    {
        return $eventCollection->addListenerQueued(TaxEvents::ENTITY_SPY_TAX_SET_TAX_DELETE, new TaxSetTaxStoragePublishListener());
    }
}
