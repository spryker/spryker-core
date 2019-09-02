<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Dependency\Facade;

use Generated\Shared\Transfer\StoreTransfer;

class ProductPackagingUnitToAvailabilityFacadeBridge implements ProductPackagingUnitToAvailabilityFacadeInterface
{
    /**
     * @var \Spryker\Zed\Availability\Business\AvailabilityFacadeInterface
     */
    protected $availabilityFacade;

    /**
     * @param \Spryker\Zed\Availability\Business\AvailabilityFacadeInterface $availabilityFacade
     */
    public function __construct($availabilityFacade)
    {
        $this->availabilityFacade = $availabilityFacade;
    }

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return int
     */
    public function calculateStockForProductWithStore($sku, StoreTransfer $storeTransfer)
    {
        return $this->availabilityFacade->calculateStockForProductWithStore($sku, $storeTransfer);
    }

    /**
     * @param string $sku
     * @param int $quantity
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return bool
     */
    public function isProductSellableForStore($sku, $quantity, StoreTransfer $storeTransfer)
    {
        return $this->availabilityFacade->isProductSellableForStore($sku, $quantity, $storeTransfer);
    }

    /**
     * @param string $sku
     * @param int $quantity
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return int
     */
    public function saveProductAvailabilityForStore($sku, $quantity, StoreTransfer $storeTransfer)
    {
        return $this->availabilityFacade->saveProductAvailabilityForStore($sku, $quantity, $storeTransfer);
    }
}
