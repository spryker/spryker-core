<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockStorage\Communication\Plugin\Event\Subscriber;

use Spryker\Zed\CmsBlock\Dependency\CmsBlockEvents;
use Spryker\Zed\CmsBlockStorage\Communication\Plugin\Event\Listener\CmsBlockGlossaryKeyMappingBlockStorageListener;
use Spryker\Zed\CmsBlockStorage\Communication\Plugin\Event\Listener\CmsBlockStorageListener;
use Spryker\Zed\CmsBlockStorage\Communication\Plugin\Event\Listener\CmsBlockStoreStorageListener;
use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CmsBlockStorage\Communication\CmsBlockStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\CmsBlockStorage\Business\CmsBlockStorageFacadeInterface getFacade()
 */
class CmsBlockStorageEventSubscriber extends AbstractPlugin implements EventSubscriberInterface
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
        $this->addCmsBlockPublishStorageListener($eventCollection);
        $this->addCmsBlockUnpublishStorageListener($eventCollection);
        $this->addCmsBlockUpdateStorageListener($eventCollection);
        $this->addCmsBlockDeleteStorageListener($eventCollection);
        $this->addCmsBlockGlossaryKeyMappingBlockCreateStorageListener($eventCollection);
        $this->addCmsBlockGlossaryKeyMappingBlockUpdateStorageListener($eventCollection);
        $this->addCmsBlockGlossaryKeyMappingBlockDeleteStorageListener($eventCollection);
        $this->addCmsBlockStoreCreateStorageListener($eventCollection);
        $this->addCmsBlockStoreUpdateStorageListener($eventCollection);
        $this->addCmsBlockStoreDeleteStorageListener($eventCollection);

        return $eventCollection;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCmsBlockPublishStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CmsBlockEvents::CMS_BLOCK_PUBLISH, new CmsBlockStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCmsBlockUnpublishStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CmsBlockEvents::CMS_BLOCK_UNPUBLISH, new CmsBlockStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCmsBlockUpdateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CmsBlockEvents::ENTITY_SPY_CMS_BLOCK_UPDATE, new CmsBlockStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCmsBlockDeleteStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CmsBlockEvents::ENTITY_SPY_CMS_BLOCK_DELETE, new CmsBlockStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCmsBlockGlossaryKeyMappingBlockCreateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CmsBlockEvents::ENTITY_SPY_CMS_BLOCK_GLOSSARY_KEY_MAPPING_CREATE, new CmsBlockGlossaryKeyMappingBlockStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCmsBlockGlossaryKeyMappingBlockUpdateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CmsBlockEvents::ENTITY_SPY_CMS_BLOCK_GLOSSARY_KEY_MAPPING_UPDATE, new CmsBlockGlossaryKeyMappingBlockStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCmsBlockGlossaryKeyMappingBlockDeleteStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CmsBlockEvents::ENTITY_SPY_CMS_BLOCK_GLOSSARY_KEY_MAPPING_DELETE, new CmsBlockGlossaryKeyMappingBlockStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCmsBlockStoreCreateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CmsBlockEvents::ENTITY_SPY_CMS_BLOCK_STORE_CREATE, new CmsBlockStoreStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCmsBlockStoreUpdateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CmsBlockEvents::ENTITY_SPY_CMS_BLOCK_STORE_UPDATE, new CmsBlockStoreStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCmsBlockStoreDeleteStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CmsBlockEvents::ENTITY_SPY_CMS_BLOCK_STORE_DELETE, new CmsBlockStoreStorageListener());
    }
}
