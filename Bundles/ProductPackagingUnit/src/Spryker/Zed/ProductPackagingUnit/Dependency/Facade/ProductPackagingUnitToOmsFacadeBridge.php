<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Dependency\Facade;

use Generated\Shared\Transfer\StoreTransfer;

class ProductPackagingUnitToOmsFacadeBridge implements ProductPackagingUnitToOmsFacadeInterface
{
    /**
     * @var \Spryker\Zed\Oms\Business\OmsFacadeInterface
     */
    protected $omsFacade;

    /**
     * @param \Spryker\Zed\Oms\Business\OmsFacadeInterface $omsFacade
     */
    public function __construct($omsFacade)
    {
        $this->omsFacade = $omsFacade;
    }

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return float
     */
    public function sumReservedProductQuantitiesForSku(string $sku, StoreTransfer $storeTransfer): float
    {
        return $this->omsFacade->sumReservedProductQuantitiesForSku($sku, $storeTransfer);
    }

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param float $reservationQuantity
     *
     * @return void
     */
    public function saveReservation(string $sku, StoreTransfer $storeTransfer, float $reservationQuantity): void
    {
        $this->omsFacade->saveReservation($sku, $storeTransfer, $reservationQuantity);
    }

    /**
     * @return string[]
     */
    public function getReservedStateNames(): array
    {
        return $this->omsFacade->getReservedStateNames();
    }
}
