<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener;

use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;

/**
 * @method \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductPageSearch\Communication\ProductPageSearchCommunicationFactory getFactory()
 */
class ProductPageAvailabilityStockSearchListener extends AbstractProductPageSearchListener implements EventBulkHandlerInterface
{
    /**
     * @uses \Orm\Zed\Availability\Persistence\Map\SpyAvailabilityTableMap::COL_SKU
     */
    protected const COL_SKU = 'spy_availability.sku';

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventEntityTransfers, $eventName)
    {
        $productConcreteSkus = $this->getFactory()
            ->getEventBehaviorFacade()
            ->getEventTransfersAdditionalValues($eventEntityTransfers, static::COL_SKU);

        $productAbstractIds = $this->getRepository()->getProductAbstractIdsByProductConcreteSkus($productConcreteSkus);

        if (!$productAbstractIds) {
            return;
        }

        $this->publish($productAbstractIds);
    }
}
