<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Business\Model\Reservation;

use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Service\ProductPackagingUnit\ProductPackagingUnitServiceInterface;
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
     * @var \Spryker\Service\ProductPackagingUnit\ProductPackagingUnitServiceInterface
     */
    protected $service;

    /**
     * @param \Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToOmsFacadeInterface $omsFacade
     * @param \Spryker\Zed\ProductPackagingUnit\Persistence\ProductPackagingUnitRepositoryInterface $productPackagingUnitRepository
     * @param \Spryker\Service\ProductPackagingUnit\ProductPackagingUnitServiceInterface $service
     */
    public function __construct(
        ProductPackagingUnitToOmsFacadeInterface $omsFacade,
        ProductPackagingUnitRepositoryInterface $productPackagingUnitRepository,
        ProductPackagingUnitServiceInterface $service
    ) {
        $this->omsFacade = $omsFacade;
        $this->productPackagingUnitRepository = $productPackagingUnitRepository;
        $this->service = $service;
    }

    /**
     * @param string $leadProductSku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return float
     */
    public function calculateReservedAmountForLeadProduct(string $leadProductSku, StoreTransfer $storeTransfer): float
    {
        $reservedStateNames = $this->omsFacade->getReservedStateNames();

        $sumReservedLeadProductAmount = $this->productPackagingUnitRepository
            ->sumLeadProductAmountForAllSalesOrderItemsBySku($leadProductSku, $reservedStateNames);

        $sumReservedLeadProductQuantity = $this->omsFacade
            ->sumReservedProductQuantitiesForSku($leadProductSku, $storeTransfer);

        $reservedAmount = $sumReservedLeadProductAmount + $sumReservedLeadProductQuantity;

        return $this->service->round($reservedAmount);
    }
}
