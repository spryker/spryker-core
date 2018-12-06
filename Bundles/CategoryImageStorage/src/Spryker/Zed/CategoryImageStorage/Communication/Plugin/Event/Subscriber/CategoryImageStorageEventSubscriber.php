<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImageStorage\Communication\Plugin\Event\Subscriber;

use Spryker\Zed\CategoryImage\Dependency\CategoryImageEvents;
use Spryker\Zed\CategoryImageStorage\Communication\Plugin\Event\Listener\CategoryImagePublishStorageListener;
use Spryker\Zed\CategoryImageStorage\Communication\Plugin\Event\Listener\CategoryImageSetCategoryImageStorageListener;
use Spryker\Zed\CategoryImageStorage\Communication\Plugin\Event\Listener\CategoryImageSetStorageListener;
use Spryker\Zed\CategoryImageStorage\Communication\Plugin\Event\Listener\CategoryImageStorageListener;
use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CategoryImageStorage\Business\CategoryImageStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\CategoryImageStorage\Communication\CategoryImageStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\CategoryImageStorage\CategoryImageStorageConfig getConfig()
 */
class CategoryImageStorageEventSubscriber extends AbstractPlugin implements EventSubscriberInterface
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
        $this->addCategoryImagePublishStorageListener($eventCollection);
        $this->addCategoryImageUnpublishStorageListener($eventCollection);
        $this->addCategoryImageUpdateStorageListener($eventCollection);
        $this->addCategoryImageSetCreateStorageListener($eventCollection);
        $this->addCategoryImageSetUpdateStorageListener($eventCollection);
        $this->addCategoryImageSetDeleteStorageListener($eventCollection);
        $this->addCategoryImageSetCategoryImageCreateStorageListener($eventCollection);
        $this->addCategoryImageSetCategoryImageUpdateStorageListener($eventCollection);
        $this->addCategoryImageSetCategoryImageDeleteStorageListener($eventCollection);

        return $eventCollection;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCategoryImagePublishStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CategoryImageEvents::CATEGORY_IMAGE_CATEGORY_PUBLISH, new CategoryImagePublishStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCategoryImageUnpublishStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CategoryImageEvents::CATEGORY_IMAGE_CATEGORY_UNPUBLISH, new CategoryImagePublishStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCategoryImageUpdateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CategoryImageEvents::ENTITY_SPY_CATEGORY_IMAGE_UPDATE, new CategoryImageStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCategoryImageSetCreateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CategoryImageEvents::ENTITY_SPY_CATEGORY_IMAGE_SET_CREATE, new CategoryImageSetStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCategoryImageSetUpdateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CategoryImageEvents::ENTITY_SPY_CATEGORY_IMAGE_SET_UPDATE, new CategoryImageSetStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCategoryImageSetDeleteStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CategoryImageEvents::ENTITY_SPY_CATEGORY_IMAGE_SET_DELETE, new CategoryImageSetStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCategoryImageSetCategoryImageCreateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CategoryImageEvents::ENTITY_SPY_CATEGORY_IMAGE_SET_TO_CATEGORY_IMAGE_CREATE, new CategoryImageSetCategoryImageStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCategoryImageSetCategoryImageUpdateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CategoryImageEvents::ENTITY_SPY_CATEGORY_IMAGE_SET_TO_CATEGORY_IMAGE_UPDATE, new CategoryImageSetCategoryImageStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCategoryImageSetCategoryImageDeleteStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CategoryImageEvents::ENTITY_SPY_CATEGORY_IMAGE_SET_TO_CATEGORY_IMAGE_DELETE, new CategoryImageSetCategoryImageStorageListener());
    }
}
