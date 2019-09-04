<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Dependency\Facade;

use Generated\Shared\Transfer\StoreTransfer;
use Spryker\DecimalObject\Decimal;

class ProductBundleToAvailabilityBridge implements ProductBundleToAvailabilityInterface
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
     * @deprecated Use isProductSellableForStore() instead.
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
     * @deprecated Use calculateStockForProductWithStore() instead.
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
     * The method check for "method_exists" is for BC for modules without multi store availability support.
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
     * The method check for "method_exists" is for BC for modules without multi store availability support.
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

        return $this->availabilityFacade->isProductSellable($sku, $quantity);
    }

    /**
     * @param int $idAvailabilityAbstract
     *
     * @return void
     */
    public function touchAvailabilityAbstract($idAvailabilityAbstract)
    {
        $this->availabilityFacade->touchAvailabilityAbstract($idAvailabilityAbstract);
    }

    /**
     * @deprecated Use saveProductAvailabilityForStore() instead.
     *
     * @param string $sku
     * @param \Spryker\DecimalObject\Decimal $quantity
     *
     * @return int
     */
    public function saveProductAvailability(string $sku, Decimal $quantity): int
    {
        return $this->availabilityFacade->saveProductAvailability($sku, $quantity);
    }

    /**
     * The method check for "method_exists" is for BC for modules without multi store availability support.
     *
     * @param string $sku
     * @param \Spryker\DecimalObject\Decimal $quantity
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return int
     */
    public function saveProductAvailabilityForStore(string $sku, Decimal $quantity, StoreTransfer $storeTransfer): int
    {
        if (method_exists($this->availabilityFacade, 'saveProductAvailabilityForStore')) {
            return $this->availabilityFacade->saveProductAvailabilityForStore($sku, $quantity, $storeTransfer);
        }

        return $this->availabilityFacade->saveProductAvailability($sku, $quantity);
    }
}
