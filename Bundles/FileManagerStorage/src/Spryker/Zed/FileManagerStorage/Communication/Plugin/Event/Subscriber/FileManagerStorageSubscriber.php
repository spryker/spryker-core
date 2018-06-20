<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerStorage\Communication\Plugin\Event\Subscriber;

use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\FileManager\Dependency\FileManagerEvents;
use Spryker\Zed\FileManagerStorage\Communication\Plugin\Event\Listener\FileInfoListener;
use Spryker\Zed\FileManagerStorage\Communication\Plugin\Event\Listener\FileListener;
use Spryker\Zed\FileManagerStorage\Communication\Plugin\Event\Listener\FileLocalizedAttributesListener;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\FileManagerStorage\Communication\FileManagerStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\FileManagerStorage\Business\FileManagerStorageFacadeInterface getFacade()
 */
class FileManagerStorageSubscriber extends AbstractPlugin implements EventSubscriberInterface
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
            ->addListenerQueued(FileManagerEvents::ENTITY_FILE_CREATE, new FileListener())
            ->addListenerQueued(FileManagerEvents::ENTITY_FILE_UPDATE, new FileListener())
            ->addListenerQueued(FileManagerEvents::ENTITY_FILE_DELETE, new FileListener())
            ->addListenerQueued(FileManagerEvents::ENTITY_FILE_INFO_CREATE, new FileInfoListener())
            ->addListenerQueued(FileManagerEvents::ENTITY_FILE_INFO_UPDATE, new FileInfoListener())
            ->addListenerQueued(FileManagerEvents::ENTITY_FILE_INFO_DELETE, new FileInfoListener())
            ->addListenerQueued(FileManagerEvents::ENTITY_FILE_LOCALIZED_ATTRIBUTES_CREATE, new FileLocalizedAttributesListener())
            ->addListenerQueued(FileManagerEvents::ENTITY_FILE_LOCALIZED_ATTRIBUTES_UPDATE, new FileLocalizedAttributesListener())
            ->addListenerQueued(FileManagerEvents::ENTITY_FILE_LOCALIZED_ATTRIBUTES_DELETE, new FileLocalizedAttributesListener());

        return $eventCollection;
    }
}
