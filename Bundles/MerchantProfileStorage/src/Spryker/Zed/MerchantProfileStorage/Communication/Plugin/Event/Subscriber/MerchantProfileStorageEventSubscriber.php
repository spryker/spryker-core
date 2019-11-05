<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileStorage\Communication\Plugin\Event\Subscriber;

use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MerchantProfile\Dependency\MerchantProfileEvents;
use Spryker\Zed\MerchantProfileStorage\Communication\Plugin\Event\Listener\MerchantProfileStoragePublishListener;
use Spryker\Zed\MerchantProfileStorage\Communication\Plugin\Event\Listener\MerchantProfileStorageUnpublishListener;

/**
 * @method \Spryker\Zed\MerchantProfileStorage\MerchantProfileStorageConfig getConfig()
 * @method \Spryker\Zed\MerchantProfileStorage\Business\MerchantProfileStorageBusinessFactory getFactory()
 * @method \Spryker\Zed\MerchantProfileStorage\Business\MerchantProfileStorageFacade getFacade()
 */
class MerchantProfileStorageEventSubscriber extends AbstractPlugin implements EventSubscriberInterface
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
        $eventCollection = $this->addMerchantProfileCreateListeners($eventCollection);
        $eventCollection = $this->addMerchantProfileUpdateListeners($eventCollection);
        $eventCollection = $this->addMerchantProfilePublishListeners($eventCollection);
        $eventCollection = $this->addMerchantProfileDeleteListeners($eventCollection);

        return $eventCollection;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return \Spryker\Zed\Event\Dependency\EventCollectionInterface
     */
    protected function addMerchantProfileCreateListeners(EventCollectionInterface $eventCollection): EventCollectionInterface
    {
        $eventCollection->addListenerQueued(MerchantProfileEvents::ENTITY_SPY_MERCHANT_PROFILE_CREATE, new MerchantProfileStoragePublishListener());

        return $eventCollection;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return \Spryker\Zed\Event\Dependency\EventCollectionInterface
     */
    protected function addMerchantProfileUpdateListeners(EventCollectionInterface $eventCollection): EventCollectionInterface
    {
        $eventCollection->addListenerQueued(MerchantProfileEvents::ENTITY_SPY_MERCHANT_PROFILE_UPDATE, new MerchantProfileStoragePublishListener());

        return $eventCollection;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return \Spryker\Zed\Event\Dependency\EventCollectionInterface
     */
    protected function addMerchantProfilePublishListeners(EventCollectionInterface $eventCollection): EventCollectionInterface
    {
        $eventCollection->addListenerQueued(MerchantProfileEvents::ENTITY_SPY_MERCHANT_PROFILE_PUBLISH, new MerchantProfileStoragePublishListener());

        return $eventCollection;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return \Spryker\Zed\Event\Dependency\EventCollectionInterface
     */
    protected function addMerchantProfileDeleteListeners(EventCollectionInterface $eventCollection): EventCollectionInterface
    {
        $eventCollection->addListenerQueued(MerchantProfileEvents::ENTITY_SPY_MERCHANT_PROFILE_DELETE, new MerchantProfileStorageUnpublishListener());

        return $eventCollection;
    }
}
