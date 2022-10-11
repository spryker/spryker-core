<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOffer\Business\Trigger;

use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Zed\ProductOffer\Dependency\Facade\ProductOfferToEventInterface;
use Spryker\Zed\ProductOffer\Dependency\ProductOfferEvents;

class ProductEventTrigger implements ProductEventTriggerInterface
{
    /**
     * @var \Spryker\Zed\ProductOffer\Dependency\Facade\ProductOfferToEventInterface
     */
    protected $eventFacade;

    /**
     * @param \Spryker\Zed\ProductOffer\Dependency\Facade\ProductOfferToEventInterface $eventFacade
     */
    public function __construct(ProductOfferToEventInterface $eventFacade)
    {
        $this->eventFacade = $eventFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return void
     */
    public function triggerProductUpdateEvent(ProductOfferTransfer $productOfferTransfer): void
    {
        if ($productOfferTransfer->getIdProductConcrete()) {
            $productUpdatedEvent = (new EventEntityTransfer())->setId($productOfferTransfer->getIdProductConcrete());

            $this->eventFacade->trigger(ProductOfferEvents::PRODUCT_CONCRETE_UPDATE, $productUpdatedEvent);
        }
    }
}
