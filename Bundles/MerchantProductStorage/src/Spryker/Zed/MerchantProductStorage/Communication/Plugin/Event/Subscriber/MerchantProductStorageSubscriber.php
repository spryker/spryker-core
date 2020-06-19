<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductStorage\Communication\Plugin\Event\Subscriber;

use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MerchantProduct\Dependency\MerchantProductEvents;
use Spryker\Zed\MerchantProductStorage\Communication\Plugin\Event\Listener\MerchantProductStoragePublishListener;
use Spryker\Zed\MerchantProductStorage\Communication\Plugin\Event\Listener\MerchantProductStorageUnpublishListener;

/**
 * @method \Spryker\Zed\MerchantProductStorage\MerchantProductStorageConfig getConfig()
 * @method \Spryker\Zed\MerchantProductStorage\Business\MerchantProductStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantProductStorage\Communication\MerchantProductStorageCommunicationFactory getFactory()
 */
class MerchantProductStorageSubscriber extends AbstractPlugin implements EventSubscriberInterface
{
    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return \Spryker\Zed\Event\Dependency\EventCollectionInterface
     */
    public function getSubscribedEvents(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(
            MerchantProductEvents::ENTITY_SPY_MERCHANT_PRODUCT_ABSTRACT_CREATE,
            new MerchantProductStoragePublishListener()
        );
        $eventCollection->addListenerQueued(
            MerchantProductEvents::MERCHANT_PRODUCT_ABSTRACT_KEY_PUBLISH,
            new MerchantProductStoragePublishListener()
        );
        $eventCollection->addListenerQueued(
            MerchantProductEvents::MERCHANT_PRODUCT_ABSTRACT_KEY_UNPUBLISH,
            new MerchantProductStorageUnpublishListener()
        );
        $eventCollection->addListenerQueued(
            MerchantProductEvents::ENTITY_SPY_MERCHANT_PRODUCT_ABSTRACT_UPDATE,
            new MerchantProductStoragePublishListener()
        );
        $eventCollection->addListenerQueued(
            MerchantProductEvents::ENTITY_SPY_MERCHANT_PRODUCT_ABSTRACT_DELETE,
            new MerchantProductStorageUnpublishListener()
        );

        return $eventCollection;
    }
}
