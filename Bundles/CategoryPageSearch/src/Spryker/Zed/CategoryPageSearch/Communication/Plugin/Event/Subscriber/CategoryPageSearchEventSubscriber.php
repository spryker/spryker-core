<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryPageSearch\Communication\Plugin\Event\Subscriber;

use Spryker\Zed\Category\Dependency\CategoryEvents;
use Spryker\Zed\CategoryPageSearch\Communication\Plugin\Event\Listener\CategoryNodeCategoryAttributeSearchListener;
use Spryker\Zed\CategoryPageSearch\Communication\Plugin\Event\Listener\CategoryNodeCategoryPageSearchListener;
use Spryker\Zed\CategoryPageSearch\Communication\Plugin\Event\Listener\CategoryNodeCategoryTemplateSearchListener;
use Spryker\Zed\CategoryPageSearch\Communication\Plugin\Event\Listener\CategoryNodeSearchListener;
use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CategoryPageSearch\Communication\CategoryPageSearchCommunicationFactory getFactory()
 * @method \Spryker\Zed\CategoryPageSearch\Business\CategoryPageSearchFacadeInterface getFacade()
 * @method \Spryker\Zed\CategoryPageSearch\CategoryPageSearchConfig getConfig()
 * @method \Spryker\Zed\CategoryPageSearch\Persistence\CategoryPageSearchQueryContainerInterface getQueryContainer()
 */
class CategoryPageSearchEventSubscriber extends AbstractPlugin implements EventSubscriberInterface
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
        $this->addCategoryPageSearchPublishListener($eventCollection);
        $this->addCategoryPageSearchUnPublishListener($eventCollection);
        $this->addCategoryNodeSearchCreateListener($eventCollection);
        $this->addCategoryNodeSearchUpdateListener($eventCollection);
        $this->addCategoryNodeSearchDeleteListener($eventCollection);
        $this->addCategoryNodeCategoryPageSearchCreateListener($eventCollection);
        $this->addCategoryNodeCategoryPageSearchUpdateListener($eventCollection);
        $this->addCategoryNodeCategoryPageSearchDeleteListener($eventCollection);
        $this->addCategoryNodeCategoryAttributeSearchCreateListener($eventCollection);
        $this->addCategoryNodeCategoryAttributeSearchUpdateListener($eventCollection);
        $this->addCategoryNodeCategoryAttributeSearchDeleteListener($eventCollection);
        $this->addCategoryNodeCategoryTemplateSearchCreateListener($eventCollection);
        $this->addCategoryNodeCategoryTemplateSearchUpdateListener($eventCollection);
        $this->addCategoryNodeCategoryTemplateSearchDeleteListener($eventCollection);

        return $eventCollection;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCategoryNodeCategoryTemplateSearchCreateListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CategoryEvents::ENTITY_SPY_CATEGORY_TEMPLATE_CREATE, new CategoryNodeCategoryTemplateSearchListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCategoryNodeCategoryTemplateSearchUpdateListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CategoryEvents::ENTITY_SPY_CATEGORY_TEMPLATE_UPDATE, new CategoryNodeCategoryTemplateSearchListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCategoryNodeCategoryTemplateSearchDeleteListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CategoryEvents::ENTITY_SPY_CATEGORY_TEMPLATE_DELETE, new CategoryNodeCategoryTemplateSearchListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCategoryNodeCategoryAttributeSearchCreateListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CategoryEvents::ENTITY_SPY_CATEGORY_ATTRIBUTE_CREATE, new CategoryNodeCategoryAttributeSearchListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCategoryNodeCategoryAttributeSearchUpdateListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CategoryEvents::ENTITY_SPY_CATEGORY_ATTRIBUTE_UPDATE, new CategoryNodeCategoryAttributeSearchListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCategoryNodeCategoryAttributeSearchDeleteListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CategoryEvents::ENTITY_SPY_CATEGORY_ATTRIBUTE_DELETE, new CategoryNodeCategoryAttributeSearchListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCategoryNodeCategoryPageSearchCreateListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CategoryEvents::ENTITY_SPY_CATEGORY_CREATE, new CategoryNodeCategoryPageSearchListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCategoryNodeCategoryPageSearchUpdateListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CategoryEvents::ENTITY_SPY_CATEGORY_UPDATE, new CategoryNodeCategoryPageSearchListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCategoryNodeCategoryPageSearchDeleteListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CategoryEvents::ENTITY_SPY_CATEGORY_DELETE, new CategoryNodeCategoryPageSearchListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCategoryPageSearchPublishListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CategoryEvents::CATEGORY_NODE_PUBLISH, new CategoryNodeSearchListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCategoryPageSearchUnPublishListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CategoryEvents::CATEGORY_NODE_UNPUBLISH, new CategoryNodeSearchListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCategoryNodeSearchCreateListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CategoryEvents::ENTITY_SPY_CATEGORY_NODE_CREATE, new CategoryNodeSearchListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCategoryNodeSearchUpdateListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CategoryEvents::ENTITY_SPY_CATEGORY_NODE_UPDATE, new CategoryNodeSearchListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCategoryNodeSearchDeleteListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CategoryEvents::ENTITY_SPY_CATEGORY_NODE_DELETE, new CategoryNodeSearchListener());
    }
}
