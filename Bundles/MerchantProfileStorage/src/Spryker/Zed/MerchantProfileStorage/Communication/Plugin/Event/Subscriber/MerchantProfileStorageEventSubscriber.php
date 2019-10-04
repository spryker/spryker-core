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
use Spryker\Zed\MerchantProfileStorage\Communication\Plugin\Event\Listener\MerchantProfileStorageActivateListener;
use Spryker\Zed\MerchantProfileStorage\Communication\Plugin\Event\Listener\MerchantProfileStorageDeactivateListener;

/**
 * @method \Spryker\Zed\MerchantProfileStorage\MerchantProfileStorageConfig getConfig()
 * @method \Spryker\Zed\MerchantProfileStorage\Business\MerchantProfileStorageBusinessFactory getFactory()
 * @method \Spryker\Zed\MerchantProfileStorage\Business\MerchantProfileStorageFacade getFacade()
 */
class MerchantProfileStorageEventSubscriber extends AbstractPlugin implements EventSubscriberInterface
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
        $eventCollection = $this->addMerchantActivateStorageListener($eventCollection);
        $eventCollection = $this->addMerchantDeactivateStorageListener($eventCollection);

        return $eventCollection;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return \Spryker\Zed\Event\Dependency\EventCollectionInterface
     */
    protected function addMerchantActivateStorageListener(EventCollectionInterface $eventCollection): EventCollectionInterface
    {
        $eventCollection->addListenerQueued(MerchantProfileEvents::ENTITY_SPY_MERCHANT_CREATE, new MerchantProfileStorageActivateListener());
        $eventCollection->addListenerQueued(MerchantProfileEvents::ENTITY_SPY_MERCHANT_UPDATE, new MerchantProfileStorageActivateListener());
        $eventCollection->addListenerQueued(MerchantProfileEvents::ENTITY_SPY_MERCHANT_PUBLISH, new MerchantProfileStorageActivateListener());

        return $eventCollection;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return \Spryker\Zed\Event\Dependency\EventCollectionInterface
     */
    protected function addMerchantDeactivateStorageListener(EventCollectionInterface $eventCollection): EventCollectionInterface
    {
        $eventCollection->addListenerQueued(MerchantProfileEvents::ENTITY_SPY_MERCHANT_UPDATE, new MerchantProfileStorageDeactivateListener());
        $eventCollection->addListenerQueued(MerchantProfileEvents::ENTITY_SPY_MERCHANT_UNPUBLISH, new MerchantProfileStorageDeactivateListener());

        return $eventCollection;
    }
}
