<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Business\Model\Oms;

use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderItemTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToOmsFacadeInterface;
use Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToStockFacadeInterface;
use Spryker\Zed\ProductPackagingUnit\Dependency\QueryContainer\ProductPackagingUnitToSalesQueryContainerInterface;

class LeadProductReservationCalculator implements LeadProductReservationCalculatorInterface
{
    protected const COL_SUM = 'SumAmount';

    /**
     * @var \Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToOmsFacadeInterface
     */
    protected $omsFacade;

    /**
     * @var \Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToStockFacadeInterface
     */
    protected $stockFacade;

    /**
     * @var \Spryker\Zed\ProductPackagingUnit\Dependency\QueryContainer\ProductPackagingUnitToSalesQueryContainerInterface
     */
    protected $salesQueryContainer;

    /**
     * @param \Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToOmsFacadeInterface $omsFacade
     * @param \Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToStockFacadeInterface $stockFacade
     * @param \Spryker\Zed\ProductPackagingUnit\Dependency\QueryContainer\ProductPackagingUnitToSalesQueryContainerInterface $salesQueryContainer
     */
    public function __construct(
        ProductPackagingUnitToOmsFacadeInterface $omsFacade,
        ProductPackagingUnitToStockFacadeInterface $stockFacade,
        ProductPackagingUnitToSalesQueryContainerInterface $salesQueryContainer
    ) {
        $this->omsFacade = $omsFacade;
        $this->stockFacade = $stockFacade;
        $this->salesQueryContainer = $salesQueryContainer;
    }

    /**
     * @param string $leadProductSku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return int
     */
    public function calculateStockForLeadProduct(string $leadProductSku, StoreTransfer $storeTransfer): int
    {
        $physicalItems = $this->stockFacade->calculateProductStockForStore($leadProductSku, $storeTransfer);
        $reservedItems = $this->omsFacade->getOmsReservedProductQuantityForSku($leadProductSku, $storeTransfer);
        $leadProductReservedAmount = $this->sumLeadProductAmountsForAllSalesOrderItemsBySku($leadProductSku);

        return $physicalItems - $reservedItems - $leadProductReservedAmount;
    }

    /**
     * @uses State
     *
     * @param string $sku
     *
     * @return int
     */
    protected function sumLeadProductAmountsForAllSalesOrderItemsBySku(string $sku): int
    {
        /** @var \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery $salesOrderItemQuery */
        $salesOrderItemQuery = $this->salesQueryContainer
            ->querySalesOrderItem()
            ->filterByAmountSku($sku)
            ->useStateQuery()
                ->filterByName($this->getReservedStateNames(), Criteria::IN)
            ->endUse()
            ->withColumn('SUM(' . SpySalesOrderItemTableMap::COL_AMOUNT . ')', static::COL_SUM)
            ->select([static::COL_SUM]);

        return (int)$salesOrderItemQuery->findOne();
    }

    /**
     * @return string[]
     */
    protected function getReservedStateNames(): array
    {
        return $this->omsFacade->getReservedStateNames();
    }
}
