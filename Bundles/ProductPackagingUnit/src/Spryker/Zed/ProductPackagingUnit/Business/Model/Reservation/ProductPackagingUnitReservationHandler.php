<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Business\Model\Reservation;

use Generated\Shared\Transfer\ProductPackagingLeadProductTransfer;
use Generated\Shared\Transfer\ProductPackagingUnitTransfer;
use Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnit\ProductPackagingUnitReaderInterface;
use Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToOmsFacadeInterface;
use Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToStoreFacadeInterface;

class ProductPackagingUnitReservationHandler implements ProductPackagingUnitReservationHandlerInterface
{
    /**
     * @var \Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnit\ProductPackagingUnitReaderInterface
     */
    protected $packagingUnitReader;

    /**
     * @var \Spryker\Zed\ProductPackagingUnit\Business\Model\Reservation\LeadProductReservationCalculatorInterface
     */
    protected $leadProductReservationCalculator;

    /**
     * @var \Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToOmsFacadeInterface
     */
    protected $omsFacade;

    /**
     * @var \Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @param \Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnit\ProductPackagingUnitReaderInterface $packagingUnitReader
     * @param \Spryker\Zed\ProductPackagingUnit\Business\Model\Reservation\LeadProductReservationCalculatorInterface $leadProductReservationCalculator
     * @param \Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToOmsFacadeInterface $omsFacade
     * @param \Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        ProductPackagingUnitReaderInterface $packagingUnitReader,
        LeadProductReservationCalculatorInterface $leadProductReservationCalculator,
        ProductPackagingUnitToOmsFacadeInterface $omsFacade,
        ProductPackagingUnitToStoreFacadeInterface $storeFacade
    ) {
        $this->packagingUnitReader = $packagingUnitReader;
        $this->leadProductReservationCalculator = $leadProductReservationCalculator;
        $this->omsFacade = $omsFacade;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param string $sku
     *
     * @return void
     */
    public function updateLeadProductReservation(string $sku): void
    {
        $productPackagingUnitTransfer = $this->findProductPackagingUnitBySku($sku);

        if (!$productPackagingUnitTransfer || !$productPackagingUnitTransfer->getHasLeadProduct()) {
            return;
        }

        $productPackagingLeadProductTransfer = $this->findProductPackagingLeadProductByProductPackagingSku($sku);
        if (!$productPackagingLeadProductTransfer) {
            return;
        }

        $this->updateReservationForLeadProduct($productPackagingLeadProductTransfer->getProduct()->getSku());
    }

    /**
     * @param string $leadProductSku
     *
     * @return void
     */
    protected function updateReservationForLeadProduct(string $leadProductSku): void
    {
        $currentStoreTransfer = $this->storeFacade->getCurrentStore();

        $stores = $currentStoreTransfer->getStoresWithSharedPersistence();
        $stores[] = $currentStoreTransfer->getName();

        foreach ($stores as $storeName) {
            $storeTransfer = $this->storeFacade->getStoreByName($storeName);

            $reservationQuantity = $this->leadProductReservationCalculator
                ->calculateReservedAmountForLeadProduct($leadProductSku, $storeTransfer);

            $this->omsFacade->saveReservation($leadProductSku, $storeTransfer, $reservationQuantity);
        }
    }

    /**
     * @param string $productPackagingUnitSku
     *
     * @return \Generated\Shared\Transfer\ProductPackagingUnitTransfer|null
     */
    protected function findProductPackagingUnitBySku(
        string $productPackagingUnitSku
    ): ?ProductPackagingUnitTransfer {
        return $this->packagingUnitReader
            ->findProductPackagingUnitByProductSku($productPackagingUnitSku);
    }

    /**
     * @param string $productPackagingUnitSku
     *
     * @return \Generated\Shared\Transfer\ProductPackagingLeadProductTransfer|null
     */
    protected function findProductPackagingLeadProductByProductPackagingSku(
        string $productPackagingUnitSku
    ): ?ProductPackagingLeadProductTransfer {
        return $this->packagingUnitReader
            ->findProductPackagingLeadProductByProductPackagingSku($productPackagingUnitSku);
    }
}
