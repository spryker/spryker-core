<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReviewSearch\Communication\Plugin\Event\Subscriber;

use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductReview\Dependency\ProductReviewEvents;
use Spryker\Zed\ProductReviewSearch\Communication\Plugin\Event\Listener\ProductReviewSearchListener;

/**
 * @method \Spryker\Zed\ProductReviewSearch\Communication\ProductReviewSearchCommunicationFactory getFactory()
 */
class ProductReviewSearchEventSubscriber extends AbstractPlugin implements EventSubscriberInterface
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
            ->addListenerQueued(ProductReviewEvents::ENTITY_SPY_PRODUCT_REVIEW_CREATE, new ProductReviewSearchListener())
            ->addListenerQueued(ProductReviewEvents::ENTITY_SPY_PRODUCT_REVIEW_UPDATE, new ProductReviewSearchListener())
            ->addListenerQueued(ProductReviewEvents::ENTITY_SPY_PRODUCT_REVIEW_DELETE, new ProductReviewSearchListener());

        return $eventCollection;
    }

}
