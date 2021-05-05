<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryStorage\Communication\Plugin\Event\Subscriber;

use Spryker\Zed\Category\Dependency\CategoryEvents;
use Spryker\Zed\CategoryStorage\Communication\Plugin\Event\Listener\CategoryNodeCategoryAttributeStoragePublishListener;
use Spryker\Zed\CategoryStorage\Communication\Plugin\Event\Listener\CategoryNodeCategoryAttributeStorageUnpublishListener;
use Spryker\Zed\CategoryStorage\Communication\Plugin\Event\Listener\CategoryNodeCategoryStoragePublishListener;
use Spryker\Zed\CategoryStorage\Communication\Plugin\Event\Listener\CategoryNodeCategoryStorageUnpublishListener;
use Spryker\Zed\CategoryStorage\Communication\Plugin\Event\Listener\CategoryNodeCategoryTemplateStoragePublishListener;
use Spryker\Zed\CategoryStorage\Communication\Plugin\Event\Listener\CategoryNodeCategoryTemplateStorageUnpublishListener;
use Spryker\Zed\CategoryStorage\Communication\Plugin\Event\Listener\CategoryNodeStorageParentPublishListener;
use Spryker\Zed\CategoryStorage\Communication\Plugin\Event\Listener\CategoryNodeStoragePublishListener;
use Spryker\Zed\CategoryStorage\Communication\Plugin\Event\Listener\CategoryNodeStorageUnpublishListener;
use Spryker\Zed\CategoryStorage\Communication\Plugin\Event\Listener\CategoryTreeStoragePublishListener;
use Spryker\Zed\CategoryStorage\Communication\Plugin\Event\Listener\CategoryTreeStorageUnpublishListener;
use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @deprecated Will be removed in the next major without replacement, registration of plugins now takes place in {@link \Pyz\Zed\Publisher\PublisherDependencyProvider::getPublisherPlugins()}.
 *
 * @method \Spryker\Zed\CategoryStorage\Communication\CategoryStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\CategoryStorage\Business\CategoryStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\CategoryStorage\CategoryStorageConfig getConfig()
 * @method \Spryker\Zed\CategoryStorage\Persistence\CategoryStorageQueryContainerInterface getQueryContainer()
 */
class CategoryStorageEventSubscriber extends AbstractPlugin implements EventSubscriberInterface
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
        $this->addCategoryTreeEvents($eventCollection);

        $this->addCategoryNodePublishListener($eventCollection);
        $this->addCategoryNodePublishParentListener($eventCollection);
        $this->addCategoryNodeUnpublishListener($eventCollection);
        $this->addCategoryNodeUnpublishParentListener($eventCollection);
        $this->addCategoryNodeCreateListener($eventCollection);
        $this->addCategoryNodeCreateParentListener($eventCollection);
        $this->addCategoryNodeUpdateListener($eventCollection);
        $this->addCategoryNodeUpdateParentListener($eventCollection);
        $this->addCategoryNodeDeleteListener($eventCollection);
        $this->addCategoryNodeDeleteParentListener($eventCollection);
        $this->addCategoryCreateListener($eventCollection);
        $this->addCategoryUpdateListener($eventCollection);
        $this->addCategoryDeleteListener($eventCollection);
        $this->addCategoryAttributeUpdateListener($eventCollection);
        $this->addCategoryAttributeCreateListener($eventCollection);
        $this->addCategoryAttributeDeleteListener($eventCollection);
        $this->addCategoryTemplateCreateListener($eventCollection);
        $this->addCategoryTemplateUpdateListener($eventCollection);
        $this->addCategoryTemplateDeleteListener($eventCollection);

        return $eventCollection;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCategoryTreeEvents(EventCollectionInterface $eventCollection)
    {
        $this->addCategoryTreePublishListener($eventCollection);
        $this->addCategoryTreeUnpublishListener($eventCollection);
        $this->addCategoryCreateForTreeListener($eventCollection);
        $this->addCategoryUpdateForTreeListener($eventCollection);
        $this->addCategoryDeleteForTreeListener($eventCollection);
        $this->addCategoryNodeCreateForTreeListener($eventCollection);
        $this->addCategoryNodeUpdateForTreeListener($eventCollection);
        $this->addCategoryNodeDeleteForTreeListener($eventCollection);
        $this->addCategoryAttributeCreateForTreeListener($eventCollection);
        $this->addCategoryAttributeUpdateForTreeListener($eventCollection);
        $this->addCategoryAttributeDeleteForTreeListener($eventCollection);
    }

    /**
     * @deprecated use {@link \Spryker\Zed\CategoryStorage\Communication\Plugin\Publisher\CategoryNode\CategoryNodeWritePublisherPlugin} instead.
     *
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCategoryNodePublishListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CategoryEvents::CATEGORY_NODE_PUBLISH, new CategoryNodeStoragePublishListener(), 0, null, $this->getConfig()->getCategoryNodeEventQueueName());
    }

    /**
     * @deprecated use {@link \Spryker\Zed\CategoryStorage\Communication\Plugin\Publisher\ParentWritePublisherPlugin} instead.
     *
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCategoryNodePublishParentListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CategoryEvents::CATEGORY_NODE_PUBLISH, new CategoryNodeStorageParentPublishListener(), 0, null, $this->getConfig()->getCategoryNodeEventQueueName());
    }

    /**
     * @deprecated use {@link \Spryker\Zed\CategoryStorage\Communication\Plugin\Publisher\CategoryNode\CategoryNodeDeletePublisherPlugin} instead.
     *
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCategoryNodeUnpublishListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CategoryEvents::CATEGORY_NODE_UNPUBLISH, new CategoryNodeStorageUnpublishListener(), 0, null, $this->getConfig()->getCategoryNodeEventQueueName());
    }

    /**
     * @deprecated use {@link \Spryker\Zed\CategoryStorage\Communication\Plugin\Publisher\ParentWritePublisherPlugin} instead.
     *
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCategoryNodeUnpublishParentListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CategoryEvents::CATEGORY_NODE_UNPUBLISH, new CategoryNodeStorageParentPublishListener(), 0, null, $this->getConfig()->getCategoryNodeEventQueueName());
    }

    /**
     * @deprecated use {@link \Spryker\Zed\CategoryStorage\Communication\Plugin\Publisher\CategoryNode\CategoryNodeWritePublisherPlugin} instead.
     *
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCategoryNodeCreateListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CategoryEvents::ENTITY_SPY_CATEGORY_NODE_CREATE, new CategoryNodeStoragePublishListener(), 0, null, $this->getConfig()->getCategoryNodeEventQueueName());
    }

    /**
     * @deprecated use {@link \Spryker\Zed\CategoryStorage\Communication\Plugin\Publisher\ParentWritePublisherPlugin} instead.
     *
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCategoryNodeCreateParentListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CategoryEvents::ENTITY_SPY_CATEGORY_NODE_CREATE, new CategoryNodeStorageParentPublishListener(), 0, null, $this->getConfig()->getCategoryNodeEventQueueName());
    }

    /**
     * @deprecated use {@link \Spryker\Zed\CategoryStorage\Communication\Plugin\Publisher\CategoryNode\CategoryNodeWritePublisherPlugin} instead.
     *
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCategoryNodeUpdateListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CategoryEvents::ENTITY_SPY_CATEGORY_NODE_UPDATE, new CategoryNodeStoragePublishListener(), 0, null, $this->getConfig()->getCategoryNodeEventQueueName());
    }

    /**
     * @deprecated use {@link \Spryker\Zed\CategoryStorage\Communication\Plugin\Publisher\ParentWritePublisherPlugin} instead.
     *
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCategoryNodeUpdateParentListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CategoryEvents::ENTITY_SPY_CATEGORY_NODE_UPDATE, new CategoryNodeStorageParentPublishListener(), 0, null, $this->getConfig()->getCategoryNodeEventQueueName());
    }

    /**
     * @deprecated use {@link \Spryker\Zed\CategoryStorage\Communication\Plugin\Publisher\CategoryNode\CategoryNodeDeletePublisherPlugin} instead.
     *
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCategoryNodeDeleteListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CategoryEvents::ENTITY_SPY_CATEGORY_NODE_DELETE, new CategoryNodeStorageUnpublishListener(), 0, null, $this->getConfig()->getCategoryNodeEventQueueName());
    }

    /**
     * @deprecated use {@link \Spryker\Zed\CategoryStorage\Communication\Plugin\Publisher\ParentWritePublisherPlugin} instead.
     *
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCategoryNodeDeleteParentListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CategoryEvents::ENTITY_SPY_CATEGORY_NODE_DELETE, new CategoryNodeStorageParentPublishListener(), 0, null, $this->getConfig()->getCategoryNodeEventQueueName());
    }

    /**
     * @deprecated use {@link \Spryker\Zed\CategoryStorage\Communication\Plugin\Publisher\Category\CategoryWritePublisherPlugin} instead.
     *
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCategoryCreateListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CategoryEvents::ENTITY_SPY_CATEGORY_CREATE, new CategoryNodeCategoryStoragePublishListener(), 0, null, $this->getConfig()->getCategoryNodeEventQueueName());
    }

    /**
     * @deprecated use {@link \Spryker\Zed\CategoryStorage\Communication\Plugin\Publisher\Category\CategoryWritePublisherPlugin} instead.
     *
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCategoryUpdateListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CategoryEvents::ENTITY_SPY_CATEGORY_UPDATE, new CategoryNodeCategoryStoragePublishListener(), 0, null, $this->getConfig()->getCategoryNodeEventQueueName());
    }

    /**
     * @deprecated use {@link \Spryker\Zed\CategoryStorage\Communication\Plugin\Publisher\Category\CategoryDeletePublisherPlugin} instead.
     *
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCategoryDeleteListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CategoryEvents::ENTITY_SPY_CATEGORY_DELETE, new CategoryNodeCategoryStorageUnpublishListener(), 0, null, $this->getConfig()->getCategoryNodeEventQueueName());
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\CategoryStorage\Communication\Plugin\Publisher\CategoryAttribute\CategoryAttributeWritePublisherPlugin} instead.
     *
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCategoryAttributeUpdateListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CategoryEvents::ENTITY_SPY_CATEGORY_ATTRIBUTE_UPDATE, new CategoryNodeCategoryAttributeStoragePublishListener(), 0, null, $this->getConfig()->getCategoryNodeEventQueueName());
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\CategoryStorage\Communication\Plugin\Publisher\CategoryAttribute\CategoryAttributeWritePublisherPlugin} instead.
     *
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCategoryAttributeCreateListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CategoryEvents::ENTITY_SPY_CATEGORY_ATTRIBUTE_CREATE, new CategoryNodeCategoryAttributeStoragePublishListener(), 0, null, $this->getConfig()->getCategoryNodeEventQueueName());
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\CategoryStorage\Communication\Plugin\Publisher\CategoryAttribute\CategoryAttributeDeletePublisherPlugin} instead.
     *
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCategoryAttributeDeleteListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CategoryEvents::ENTITY_SPY_CATEGORY_ATTRIBUTE_DELETE, new CategoryNodeCategoryAttributeStorageUnpublishListener(), 0, null, $this->getConfig()->getCategoryNodeEventQueueName());
    }

    /**
     * @deprecated use {@link \Spryker\Zed\CategoryStorage\Communication\Plugin\Publisher\CategoryTemplate\CategoryTemplateWritePublisherPlugin} instead.
     *
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCategoryTemplateCreateListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CategoryEvents::ENTITY_SPY_CATEGORY_TEMPLATE_CREATE, new CategoryNodeCategoryTemplateStoragePublishListener(), 0, null, $this->getConfig()->getCategoryNodeEventQueueName());
    }

    /**
     * @deprecated use {@link \Spryker\Zed\CategoryStorage\Communication\Plugin\Publisher\CategoryTemplate\CategoryTemplateWritePublisherPlugin} instead.
     *
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCategoryTemplateUpdateListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CategoryEvents::ENTITY_SPY_CATEGORY_TEMPLATE_UPDATE, new CategoryNodeCategoryTemplateStoragePublishListener(), 0, null, $this->getConfig()->getCategoryNodeEventQueueName());
    }

    /**
     * @deprecated use {@link \Spryker\Zed\CategoryStorage\Communication\Plugin\Publisher\CategoryTemplate\CategoryTemplateDeletePublisherPlugin} instead.
     *
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCategoryTemplateDeleteListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CategoryEvents::ENTITY_SPY_CATEGORY_TEMPLATE_DELETE, new CategoryNodeCategoryTemplateStorageUnpublishListener(), 0, null, $this->getConfig()->getCategoryNodeEventQueueName());
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\CategoryStorage\Communication\Plugin\Publisher\CategoryTree\CategoryTreeWriteForPublishingPublisherPlugin} instead.
     *
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCategoryTreePublishListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CategoryEvents::CATEGORY_TREE_PUBLISH, new CategoryTreeStoragePublishListener(), 0, null, $this->getConfig()->getCategoryTreeEventQueueName());
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\CategoryStorage\Communication\Plugin\Publisher\CategoryTree\CategoryTreeDeletePublisherPlugin} instead.
     *
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCategoryTreeUnpublishListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CategoryEvents::CATEGORY_TREE_UNPUBLISH, new CategoryTreeStorageUnpublishListener(), 0, null, $this->getConfig()->getCategoryTreeEventQueueName());
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\CategoryStorage\Communication\Plugin\Publisher\CategoryTree\CategoryTreeWriteForPublishingPublisherPlugin} instead.
     *
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCategoryCreateForTreeListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CategoryEvents::ENTITY_SPY_CATEGORY_CREATE, new CategoryTreeStoragePublishListener(), 0, null, $this->getConfig()->getCategoryTreeEventQueueName());
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\CategoryStorage\Communication\Plugin\Publisher\CategoryTree\CategoryTreeWriteForPublishingPublisherPlugin} instead.
     *
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCategoryUpdateForTreeListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CategoryEvents::ENTITY_SPY_CATEGORY_UPDATE, new CategoryTreeStoragePublishListener(), 0, null, $this->getConfig()->getCategoryTreeEventQueueName());
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\CategoryStorage\Communication\Plugin\Publisher\CategoryTree\CategoryTreeWriteForPublishingPublisherPlugin} instead.
     *
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCategoryDeleteForTreeListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CategoryEvents::ENTITY_SPY_CATEGORY_DELETE, new CategoryTreeStoragePublishListener(), 0, null, $this->getConfig()->getCategoryTreeEventQueueName());
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\CategoryStorage\Communication\Plugin\Publisher\CategoryTree\CategoryTreeWriteForPublishingPublisherPlugin} instead.
     *
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCategoryNodeCreateForTreeListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CategoryEvents::ENTITY_SPY_CATEGORY_NODE_CREATE, new CategoryTreeStoragePublishListener(), 0, null, $this->getConfig()->getCategoryTreeEventQueueName());
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\CategoryStorage\Communication\Plugin\Publisher\CategoryTree\CategoryTreeWriteForPublishingPublisherPlugin} instead.
     *
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCategoryNodeUpdateForTreeListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CategoryEvents::ENTITY_SPY_CATEGORY_NODE_UPDATE, new CategoryTreeStoragePublishListener(), 0, null, $this->getConfig()->getCategoryTreeEventQueueName());
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\CategoryStorage\Communication\Plugin\Publisher\CategoryTree\CategoryTreeWriteForPublishingPublisherPlugin} instead.
     *
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCategoryNodeDeleteForTreeListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CategoryEvents::ENTITY_SPY_CATEGORY_NODE_DELETE, new CategoryTreeStoragePublishListener(), 0, null, $this->getConfig()->getCategoryTreeEventQueueName());
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\CategoryStorage\Communication\Plugin\Publisher\CategoryTree\CategoryTreeWriteForPublishingPublisherPlugin} instead.
     *
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCategoryAttributeCreateForTreeListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CategoryEvents::ENTITY_SPY_CATEGORY_ATTRIBUTE_CREATE, new CategoryTreeStoragePublishListener(), 0, null, $this->getConfig()->getCategoryTreeEventQueueName());
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\CategoryStorage\Communication\Plugin\Publisher\CategoryTree\CategoryTreeWriteForPublishingPublisherPlugin} instead.
     *
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCategoryAttributeUpdateForTreeListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CategoryEvents::ENTITY_SPY_CATEGORY_ATTRIBUTE_UPDATE, new CategoryTreeStoragePublishListener(), 0, null, $this->getConfig()->getCategoryTreeEventQueueName());
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\CategoryStorage\Communication\Plugin\Publisher\CategoryTree\CategoryTreeWriteForPublishingPublisherPlugin} instead.
     *
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCategoryAttributeDeleteForTreeListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CategoryEvents::ENTITY_SPY_CATEGORY_ATTRIBUTE_DELETE, new CategoryTreeStoragePublishListener(), 0, null, $this->getConfig()->getCategoryTreeEventQueueName());
    }
}
