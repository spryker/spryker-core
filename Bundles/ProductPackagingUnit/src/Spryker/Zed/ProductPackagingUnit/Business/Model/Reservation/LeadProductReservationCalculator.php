<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Business\Model\Reservation;

use Generated\Shared\Transfer\SalesOrderItemStateAggregationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToOmsFacadeInterface;
use Spryker\Zed\ProductPackagingUnit\Persistence\ProductPackagingUnitRepositoryInterface;

class LeadProductReservationCalculator implements LeadProductReservationCalculatorInterface
{
    /**
     * @var \Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToOmsFacadeInterface
     */
    protected $omsFacade;

    /**
     * @var \Spryker\Zed\ProductPackagingUnit\Persistence\ProductPackagingUnitRepositoryInterface
     */
    protected $productPackagingUnitRepository;

    /**
     * @param \Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToOmsFacadeInterface $omsFacade
     * @param \Spryker\Zed\ProductPackagingUnit\Persistence\ProductPackagingUnitRepositoryInterface $productPackagingUnitRepository
     */
    public function __construct(
        ProductPackagingUnitToOmsFacadeInterface $omsFacade,
        ProductPackagingUnitRepositoryInterface $productPackagingUnitRepository
    ) {
        $this->omsFacade = $omsFacade;
        $this->productPackagingUnitRepository = $productPackagingUnitRepository;
    }

    /**
     * @param string $leadProductSku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return int
     */
    public function calculateReservedAmountForLeadProduct(string $leadProductSku, StoreTransfer $storeTransfer): int
    {
        $reservedStates = $this->omsFacade->getOmsReservedStateCollection()->getStates();

        $reservedLeadProductAmountAggregations = $this->productPackagingUnitRepository
            ->aggregateLeadProductAmountForAllSalesOrderItemsBySku($leadProductSku, array_keys($reservedStates->getArrayCopy()));

        $sumReservedLeadProductAmount = 0;
        foreach ($reservedLeadProductAmountAggregations as $reservedLeadProductAmountAggregation) {
            $this->assertAggregationTransfer($reservedLeadProductAmountAggregation);

            $processName = $reservedLeadProductAmountAggregation->getProcessName();
            $stateName = $reservedLeadProductAmountAggregation->getStateName();
            if (!$reservedStates->offsetExists($stateName) || !$reservedStates[$stateName]->getProcesses()->offsetExists($processName)) {
                continue;
            }

            $reservedLeadProductAmountAggregation->requireSumAmount();
            $sumReservedLeadProductAmount += $reservedLeadProductAmountAggregation->getSumAmount();
        }

        $sumReservedLeadProductQuantity = $this->omsFacade->sumReservedProductQuantitiesForSku($leadProductSku, $storeTransfer);

        return $sumReservedLeadProductAmount + $sumReservedLeadProductQuantity;
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderItemStateAggregationTransfer $salesAggregationTransfer
     *
     * @return void
     */
    protected function assertAggregationTransfer(SalesOrderItemStateAggregationTransfer $salesAggregationTransfer): void
    {
        $salesAggregationTransfer
            ->requireSku()
            ->requireProcessName()
            ->requireStateName();
    }
}
