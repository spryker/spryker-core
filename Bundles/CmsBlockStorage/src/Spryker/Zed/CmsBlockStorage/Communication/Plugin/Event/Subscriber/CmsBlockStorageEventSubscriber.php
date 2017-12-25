<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockStorage\Communication\Plugin\Event\Subscriber;

use Spryker\Zed\CmsBlock\Dependency\CmsBlockEvents;
use Spryker\Zed\CmsBlockStorage\Communication\Plugin\Event\Listener\CmsBlockGlossaryKeyMappingBlockStorageListener;
use Spryker\Zed\CmsBlockStorage\Communication\Plugin\Event\Listener\CmsBlockStorageListener;
use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CmsBlockStorage\Communication\CmsBlockStorageCommunicationFactory getFactory()
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
        $eventCollection
            ->addListenerQueued(CmsBlockEvents::CMS_BLOCK_PUBLISH, new CmsBlockStorageListener())
            ->addListenerQueued(CmsBlockEvents::CMS_BLOCK_UNPUBLISH, new CmsBlockStorageListener())
            ->addListenerQueued(CmsBlockEvents::ENTITY_SPY_CMS_BLOCK_UPDATE, new CmsBlockStorageListener())
            ->addListenerQueued(CmsBlockEvents::ENTITY_SPY_CMS_BLOCK_DELETE, new CmsBlockStorageListener())
            ->addListenerQueued(CmsBlockEvents::ENTITY_SPY_CMS_BLOCK_GLOSSARY_KEY_MAPPING_CREATE, new CmsBlockGlossaryKeyMappingBlockStorageListener())
            ->addListenerQueued(CmsBlockEvents::ENTITY_SPY_CMS_BLOCK_GLOSSARY_KEY_MAPPING_CREATE, new CmsBlockGlossaryKeyMappingBlockStorageListener())
            ->addListenerQueued(CmsBlockEvents::ENTITY_SPY_CMS_BLOCK_GLOSSARY_KEY_MAPPING_UPDATE, new CmsBlockGlossaryKeyMappingBlockStorageListener())
            ->addListenerQueued(CmsBlockEvents::ENTITY_SPY_CMS_BLOCK_GLOSSARY_KEY_MAPPING_DELETE, new CmsBlockGlossaryKeyMappingBlockStorageListener());

        return $eventCollection;
    }
}
