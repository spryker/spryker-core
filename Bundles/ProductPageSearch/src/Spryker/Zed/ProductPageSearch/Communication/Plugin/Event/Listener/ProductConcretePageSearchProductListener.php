<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener;

use Spryker\Zed\Product\Dependency\ProductEvents;

/**
 * @method \Spryker\Zed\ProductPageSearch\Communication\ProductPageSearchCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductPageSearch\ProductPageSearchConfig getConfig()
 * @method \Spryker\Zed\ProductPageSearch\Business\ProductPageSearchFacadeInterface getFacade()
 */
class ProductConcretePageSearchProductListener extends AbstractProductConcretePageSearchListener
{
    /**
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventEntityTransfers, $eventName): void
    {
        $productConcreteIds = $this->getFactory()
            ->getEventBehaviorFacade()
            ->getEventTransferIds($eventEntityTransfers);

        if ($eventName === ProductEvents::ENTITY_SPY_PRODUCT_DELETE || $eventName === ProductEvents::PRODUCT_CONCRETE_UNPUBLISH) {
            $this->unpublish($productConcreteIds);
        }

        if ($eventName === ProductEvents::ENTITY_SPY_PRODUCT_CREATE || $eventName === ProductEvents::ENTITY_SPY_PRODUCT_UPDATE || $eventName === ProductEvents::PRODUCT_CONCRETE_PUBLISH) {
            $this->publish($productConcreteIds);
        }
    }
}
