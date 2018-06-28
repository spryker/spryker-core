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
     * @param string $sku
     * @param bool $returnTest
     *
     * @return int
     */
    protected function sumLeadProductAmountsForAllSalesOrderItemsBySku(string $sku, bool $returnTest = true): int
    {
        $salesOrderItemQuery = $this
            ->salesQueryContainer
            ->querySalesOrderItem()
            ->withColumn('SUM(' . SpySalesOrderItemTableMap::COL_AMOUNT . ')', 'Sum')
            ->select(['Sum']);

        if ($returnTest === false) {
            $salesOrderItemQuery->useOrderQuery()
                ->filterByIsTest(false)
                ->endUse();
        }

        $reservedStateNames = $this->omsFacade->getReservedStateNames();
        $salesOrderItemQuery->useStateQuery()
            ->filterByName($reservedStateNames, Criteria::IN)
            ->endUse()
            ->filterByAmountSku($sku);

        return (int)$salesOrderItemQuery->findOne();
    }
}
