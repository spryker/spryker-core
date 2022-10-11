<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReview\Business\Trigger;

use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\ProductReviewTransfer;
use Spryker\Zed\ProductReview\Dependency\Facade\ProductReviewToEventInterface;
use Spryker\Zed\ProductReview\Dependency\ProductReviewEvents;

class ProductEventTrigger implements ProductEventTriggerInterface
{
    /**
     * @var string
     */
    protected const COLUMN_FK_PRODUCT_ABSTRACT = 'fk_product_abstract';

    /**
     * @var \Spryker\Zed\ProductReview\Dependency\Facade\ProductReviewToEventInterface
     */
    protected $eventFacade;

    /**
     * @param \Spryker\Zed\ProductReview\Dependency\Facade\ProductReviewToEventInterface $eventFacade
     */
    public function __construct(ProductReviewToEventInterface $eventFacade)
    {
        $this->eventFacade = $eventFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductReviewTransfer $productReviewTransfer
     *
     * @return void
     */
    public function triggerProductUpdateEvent(ProductReviewTransfer $productReviewTransfer): void
    {
        $productUpdatedEvent = (new EventEntityTransfer())->setForeignKeys([
            static::COLUMN_FK_PRODUCT_ABSTRACT => $productReviewTransfer->getFkProductAbstractOrFail(),
        ]);

        $this->eventFacade->trigger(ProductReviewEvents::PRODUCT_CONCRETE_UPDATE, $productUpdatedEvent);
    }
}
