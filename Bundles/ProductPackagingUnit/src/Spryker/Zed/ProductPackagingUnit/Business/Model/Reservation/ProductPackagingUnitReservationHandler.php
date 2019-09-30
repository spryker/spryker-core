<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Business\Model\Reservation;

use Generated\Shared\Transfer\ProductPackagingLeadProductTransfer;
use Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnit\ProductPackagingUnitReaderInterface;
use Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToOmsFacadeInterface;

class ProductPackagingUnitReservationHandler implements ProductPackagingUnitReservationHandlerInterface
{
    /**
     * @var \Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnit\ProductPackagingUnitReaderInterface
     */
    protected $packagingUnitReader;

    /**
     * @var \Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToOmsFacadeInterface
     */
    protected $omsFacade;

    /**
     * @param \Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnit\ProductPackagingUnitReaderInterface $packagingUnitReader
     * @param \Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToOmsFacadeInterface $omsFacade
     */
    public function __construct(
        ProductPackagingUnitReaderInterface $packagingUnitReader,
        ProductPackagingUnitToOmsFacadeInterface $omsFacade
    ) {
        $this->packagingUnitReader = $packagingUnitReader;
        $this->omsFacade = $omsFacade;
    }

    /**
     * @param string $sku
     *
     * @return void
     */
    public function updateLeadProductReservation(string $sku): void
    {
        $productPackagingLeadProductTransfer = $this->findProductPackagingLeadProductByProductPackagingSku($sku);

        if (!$productPackagingLeadProductTransfer) {
            return;
        }

        if ($sku === $productPackagingLeadProductTransfer->getProduct()->getSku()) {
            return;
        }

        $this->omsFacade->updateReservationQuantity($productPackagingLeadProductTransfer->getProduct()->getSku());
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
