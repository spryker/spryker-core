<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityCartConnector\Dependency\Facade;

use Generated\Shared\Transfer\StoreTransfer;
use Spryker\DecimalObject\Decimal;

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
     * @param \Spryker\DecimalObject\Decimal $quantity
     *
     * @return bool
     */
    public function isProductSellable(string $sku, Decimal $quantity): bool
    {
        return $this->availabilityFacade->isProductSellable($sku, $quantity);
    }

    /**
     * @deprecated Use calculateStockForProduct() instead.
     *
     * @param string $sku
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    public function calculateStockForProduct(string $sku): Decimal
    {
        return $this->availabilityFacade->calculateStockForProduct($sku);
    }

    /**
     * The method check for "method_exists" is for BC for modules without multi store support.
     *
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    public function calculateStockForProductWithStore(string $sku, StoreTransfer $storeTransfer): Decimal
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
     * @param \Spryker\DecimalObject\Decimal $quantity
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return bool
     */
    public function isProductSellableForStore(string $sku, Decimal $quantity, StoreTransfer $storeTransfer): bool
    {
        if (method_exists($this->availabilityFacade, 'isProductSellableForStore')) {
            return $this->availabilityFacade->isProductSellableForStore($sku, $quantity, $storeTransfer);
        }

        return (bool)$this->availabilityFacade->calculateStockForProduct($sku);
    }
}
