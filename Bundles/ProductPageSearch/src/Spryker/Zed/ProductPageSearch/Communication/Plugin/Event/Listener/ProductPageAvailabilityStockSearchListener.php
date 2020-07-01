<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener;

use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

/**
 * @method \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductPageSearch\Communication\ProductPageSearchCommunicationFactory getFactory()
 */
class ProductPageAvailabilityStockSearchListener extends AbstractProductPageSearchListener implements EventBulkHandlerInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @uses \Orm\Zed\Availability\Persistence\Map\SpyAvailabilityTableMap::COL_SKU
     */
    protected const COL_SKU = 'spy_availability.sku';

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventTransfers, $eventName)
    {
        $this->preventTransaction();

        $productConcreteSkus = $this->getFactory()
            ->getEventBehaviorFacade()
            ->getEventTransfersAdditionalValues($eventTransfers, static::COL_SKU);

        $productAbstractIds = $this->getRepository()->getProductAbstractIdsByProductConcreteSkus($productConcreteSkus);

        if (!$productAbstractIds) {
            return;
        }

        $this->publish($productAbstractIds);
    }
}
