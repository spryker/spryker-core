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
        $eventCollection
            ->addListenerQueued(ProductReviewEvents::PRODUCT_ABSTRACT_REVIEW_PUBLISH, new ProductReviewPublishStorageListener())
            ->addListenerQueued(ProductReviewEvents::PRODUCT_ABSTRACT_REVIEW_UNPUBLISH, new ProductReviewPublishStorageListener())
            ->addListenerQueued(ProductReviewEvents::ENTITY_SPY_PRODUCT_REVIEW_CREATE, new ProductReviewStorageListener())
            ->addListenerQueued(ProductReviewEvents::ENTITY_SPY_PRODUCT_REVIEW_UPDATE, new ProductReviewStorageListener())
            ->addListenerQueued(ProductReviewEvents::ENTITY_SPY_PRODUCT_REVIEW_DELETE, new ProductReviewStorageListener());

        return $eventCollection;
    }

}
