<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Business\Model\Reservation;

use Generated\Shared\Transfer\OmsStateCollectionTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToOmsFacadeInterface;
use Spryker\Zed\ProductPackagingUnit\Persistence\ProductPackagingUnitRepositoryInterface;

class ProductPackagingUnitReservationHandler implements ProductPackagingUnitReservationHandlerInterface
{
    /**
     * @var \Spryker\Zed\ProductPackagingUnit\Persistence\ProductPackagingUnitRepositoryInterface
     */
    protected $productPackagingUnitRepository;

    /**
     * @var \Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToOmsFacadeInterface
     */
    protected $omsFacade;

    /**
     * @param \Spryker\Zed\ProductPackagingUnit\Persistence\ProductPackagingUnitRepositoryInterface $productPackagingUnitRepository
     * @param \Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToOmsFacadeInterface $omsFacade
     */
    public function __construct(
        ProductPackagingUnitRepositoryInterface $productPackagingUnitRepository,
        ProductPackagingUnitToOmsFacadeInterface $omsFacade
    ) {
        $this->productPackagingUnitRepository = $productPackagingUnitRepository;
        $this->omsFacade = $omsFacade;
    }

    /**
     * @param string $sku
     *
     * @return void
     */
    public function updateLeadProductReservation(string $sku): void
    {
        $productPackagingLeadProductTransfer = $this->productPackagingUnitRepository
            ->findProductPackagingUnitLeadProductForPackagingUnit($sku);

        if (!$productPackagingLeadProductTransfer) {
            return;
        }

        if ($sku === $productPackagingLeadProductTransfer->getSku()) {
            return;
        }

        $this->omsFacade->updateReservationQuantity($productPackagingLeadProductTransfer->getSku());
    }

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\OmsStateCollectionTransfer $reservedStates
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemStateAggregationTransfer[]
     */
    public function aggregateProductPackagingUnitReservation(string $sku, OmsStateCollectionTransfer $reservedStates, StoreTransfer $storeTransfer): array
    {
        $storeTransfer->requireName();
        $reservedStates->requireStates();

        return $this->productPackagingUnitRepository->aggregateProductPackagingUnitReservation(
            $sku,
            array_keys($reservedStates->getStates()->getArrayCopy()),
            $storeTransfer
        );
    }
}
