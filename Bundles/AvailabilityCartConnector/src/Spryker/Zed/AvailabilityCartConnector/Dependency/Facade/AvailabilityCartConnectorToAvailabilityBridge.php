<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityCartConnector\Dependency\Facade;

use Generated\Shared\Transfer\StoreTransfer;

class AvailabilityCartConnectorToAvailabilityBridge implements AvailabilityCartConnectorToAvailabilityInterface
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
     * @deprecated Use calculateStockForProductWithStore() instead.
     *
     * @param string $sku
     * @param float $quantity
     *
     * @return bool
     */
    public function isProductSellable($sku, $quantity)
    {
        return $this->availabilityFacade->isProductSellable($sku, $quantity);
    }

    /**
     * @deprecated Use calculateStockForProduct() instead.
     *
     * @param string $sku
     *
     * @return int
     */
    public function calculateStockForProduct($sku)
    {
        return $this->availabilityFacade->calculateStockForProduct($sku);
    }

    /**
     * The method check for "method_exists" is for BC for modules without multi store support.
     *
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return int
     */
    public function calculateStockForProductWithStore($sku, StoreTransfer $storeTransfer)
    {
        if (method_exists($this->availabilityFacade, 'calculateStockForProductWithStore')) {
            return $this->availabilityFacade->calculateStockForProductWithStore($sku, $storeTransfer);
        }

        return $this->availabilityFacade->calculateStockForProduct($sku);
    }

    /**
     * The method check for "method_exists" is for BC for modules without multi store support.
     *
     * @param string $sku
     * @param float $quantity
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return bool
     */
    public function isProductSellableForStore($sku, $quantity, StoreTransfer $storeTransfer)
    {
        if (method_exists($this->availabilityFacade, 'isProductSellableForStore')) {
            return $this->availabilityFacade->isProductSellableForStore($sku, $quantity, $storeTransfer);
        }

        return (bool)$this->availabilityFacade->calculateStockForProduct($sku);
    }
}
