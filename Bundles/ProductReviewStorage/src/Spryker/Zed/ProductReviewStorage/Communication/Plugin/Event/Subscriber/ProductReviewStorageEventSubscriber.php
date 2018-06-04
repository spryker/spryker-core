<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReviewStorage\Communication\Plugin\Event\Subscriber;

use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductReview\Dependency\ProductReviewEvents;
use Spryker\Zed\ProductReviewStorage\Communication\Plugin\Event\Listener\ProductReviewPublishStorageListener;
use Spryker\Zed\ProductReviewStorage\Communication\Plugin\Event\Listener\ProductReviewStorageListener;

/**
 * @method \Spryker\Zed\ProductReviewStorage\Communication\ProductReviewStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductReviewStorage\Business\ProductReviewStorageFacadeInterface getFacade()
 */
class ProductReviewStorageEventSubscriber extends AbstractPlugin implements EventSubscriberInterface
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
        $this->addProductReviewPublishStorageListener($eventCollection);
        $this->addProductReviewUnpublishStorageListener($eventCollection);
        $this->addProductReviewCreateStorageListener($eventCollection);
        $this->addProductReviewUpdateStorageListener($eventCollection);
        $this->addProductReviewDeleteStorageListener($eventCollection);

        return $eventCollection;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductReviewPublishStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductReviewEvents::PRODUCT_ABSTRACT_REVIEW_PUBLISH, new ProductReviewPublishStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductReviewUnpublishStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductReviewEvents::PRODUCT_ABSTRACT_REVIEW_UNPUBLISH, new ProductReviewPublishStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductReviewCreateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductReviewEvents::ENTITY_SPY_PRODUCT_REVIEW_CREATE, new ProductReviewStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductReviewUpdateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductReviewEvents::ENTITY_SPY_PRODUCT_REVIEW_UPDATE, new ProductReviewStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductReviewDeleteStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductReviewEvents::ENTITY_SPY_PRODUCT_REVIEW_DELETE, new ProductReviewStorageListener());
    }
}
