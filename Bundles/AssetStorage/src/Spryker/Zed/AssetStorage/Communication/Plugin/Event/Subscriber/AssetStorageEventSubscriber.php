<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetStorage\Communication\Plugin\Event\Subscriber;

use Spryker\Zed\Asset\Dependency\AssetEvents;
use Spryker\Zed\AssetStorage\Communication\Plugin\Event\Listener\AssetStoragePublishListener;
use Spryker\Zed\AssetStorage\Communication\Plugin\Event\Listener\AssetStorageUnpublishListener;
use Spryker\Zed\AssetStorage\Communication\Plugin\Event\Listener\AssetStoreStoragePublishListener;
use Spryker\Zed\AssetStorage\Communication\Plugin\Event\Listener\AssetStoreStorageUnpublishListener;
use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * Will be removed in the next major without replacement, registration of plugins now takes place in {@link \Spryker\Zed\Publisher\PublisherDependencyProvider::getPublisherPlugins()}.
 *
 * @method \Spryker\Zed\AssetStorage\AssetStorageConfig getConfig()
 * @method \Spryker\Zed\AssetStorage\Business\AssetStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\AssetStorage\Communication\AssetStorageCommunicationFactory getFactory()
 */
class AssetStorageEventSubscriber extends AbstractPlugin implements EventSubscriberInterface
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
        $this->addAssetCreateStorageListener($eventCollection);
        $this->addAssetUpdateStorageListener($eventCollection);
        $this->addAssetDeleteStorageListener($eventCollection);
        $this->addAssetStoreCreateStorageListener($eventCollection);
        $this->addAssetStoreDeleteStorageListener($eventCollection);

        return $eventCollection;
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\AssetStorage\Communication\Plugin\Publisher\Asset\AssetWritePublisherPlugin} instead.
     *
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return \Spryker\Zed\Event\Dependency\EventCollectionInterface
     */
    protected function addAssetCreateStorageListener(EventCollectionInterface $eventCollection): EventCollectionInterface
    {
        return $eventCollection->addListenerQueued(AssetEvents::ENTITY_SPY_ASSET_CREATE, new AssetStoragePublishListener(), 0, null, $this->getConfig()->findEventQueueName());
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\AssetStorage\Communication\Plugin\Publisher\Asset\AssetWritePublisherPlugin} instead.
     *
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return \Spryker\Zed\Event\Dependency\EventCollectionInterface
     */
    protected function addAssetUpdateStorageListener(EventCollectionInterface $eventCollection): EventCollectionInterface
    {
        return $eventCollection->addListenerQueued(AssetEvents::ENTITY_SPY_ASSET_UPDATE, new AssetStoragePublishListener(), 0, null, $this->getConfig()->findEventQueueName());
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\AssetStorage\Communication\Plugin\Publisher\Asset\AssetDeletePublisherPlugin} instead.
     *
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return \Spryker\Zed\Event\Dependency\EventCollectionInterface
     */
    protected function addAssetDeleteStorageListener(EventCollectionInterface $eventCollection): EventCollectionInterface
    {
        return $eventCollection->addListenerQueued(AssetEvents::ENTITY_SPY_ASSET_DELETE, new AssetStorageUnpublishListener(), 0, null, $this->getConfig()->findEventQueueName());
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\AssetStorage\Communication\Plugin\Publisher\Asset\AssetWritePublisherPlugin} instead.
     *
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return \Spryker\Zed\Event\Dependency\EventCollectionInterface
     */
    protected function addAssetStoreCreateStorageListener(EventCollectionInterface $eventCollection): EventCollectionInterface
    {
        return $eventCollection->addListenerQueued(AssetEvents::ENTITY_SPY_ASSET_STORE_CREATE, new AssetStoreStoragePublishListener(), 0, null, $this->getConfig()->findEventQueueName());
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\AssetStorage\Communication\Plugin\Publisher\Asset\AssetDeletePublisherPlugin} instead.
     *
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return \Spryker\Zed\Event\Dependency\EventCollectionInterface
     */
    protected function addAssetStoreDeleteStorageListener(EventCollectionInterface $eventCollection): EventCollectionInterface
    {
        return $eventCollection->addListenerQueued(AssetEvents::ENTITY_SPY_ASSET_STORE_DELETE, new AssetStoreStorageUnpublishListener(), 0, null, $this->getConfig()->findEventQueueName());
    }
}
