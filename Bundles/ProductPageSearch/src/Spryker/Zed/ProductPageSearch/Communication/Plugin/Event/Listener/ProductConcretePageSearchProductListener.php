<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener;

use Spryker\Zed\Product\Dependency\ProductEvents;

/**
 * @method \Spryker\Zed\ProductPageSearch\Communication\ProductPageSearchCommunicationFactory getFactory()
 */
class ProductConcretePageSearchProductListener extends AbstractProductConcretePageSearchListener
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventTransfers, $eventName): void
    {
        $this->preventTransaction();
        $productConcreteIds = $this->getFactory()
            ->getEventBehaviorFacade()
            ->getEventTransferIds($eventTransfers);

        if ($eventName === ProductEvents::ENTITY_SPY_PRODUCT_DELETE || $eventName === ProductEvents::PRODUCT_CONCRETE_UNPUBLISH) {
            $this->unpublish($productConcreteIds);
        }

        if ($eventName === ProductEvents::ENTITY_SPY_PRODUCT_CREATE || $eventName === ProductEvents::ENTITY_SPY_PRODUCT_UPDATE || $eventName === ProductEvents::PRODUCT_CONCRETE_PUBLISH) {
            $this->publish($productConcreteIds);
        }
    }
}
