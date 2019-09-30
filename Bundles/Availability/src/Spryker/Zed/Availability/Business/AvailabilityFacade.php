<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Availability\Business;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\ProductConcreteAvailabilityRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\DecimalObject\Decimal;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Availability\Business\AvailabilityBusinessFactory getFactory()
 */
class AvailabilityFacade extends AbstractFacade implements AvailabilityFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use isProductSellableForStore() instead.
     *
     * @param string $sku
     * @param \Spryker\DecimalObject\Decimal $quantity
     *
     * @return bool
     */
    public function isProductSellable(string $sku, Decimal $quantity): bool
    {
        return $this->getFactory()
            ->createSellableModel()
            ->isProductSellable($sku, $quantity);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $sku
     * @param \Spryker\DecimalObject\Decimal $quantity
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return bool
     */
    public function isProductSellableForStore(string $sku, Decimal $quantity, StoreTransfer $storeTransfer): bool
    {
        return $this->getFactory()
            ->createSellableModel()
            ->isProductSellableForStore($sku, $quantity, $storeTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductConcrete
     *
     * @return bool
     */
    public function isProductConcreteAvailable(int $idProductConcrete): bool
    {
        return $this->getFactory()
            ->createSellableModel()
            ->isProductConcreteAvailable($idProductConcrete);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use calculateStockForProductWithStore() instead.
     *
     * @param string $sku
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    public function calculateAvailabilityForProduct(string $sku): Decimal
    {
        return $this->getFactory()
            ->createSellableModel()
            ->calculateAvailabilityForProduct($sku);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    public function calculateAvailabilityForProductWithStore(string $sku, StoreTransfer $storeTransfer): Decimal
    {
        return $this->getFactory()
            ->createSellableModel()
            ->calculateAvailabilityForProductWithStore($sku, $storeTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function checkoutAvailabilityPreCondition(
        QuoteTransfer $quoteTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer
    ) {
        return $this->getFactory()
            ->createProductsAvailablePreCondition()
            ->checkCondition($quoteTransfer, $checkoutResponseTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use updateAvailabilityForStore() instead.
     *
     * @param string $sku
     *
     * @return void
     */
    public function updateAvailability($sku)
    {
        $this->getFactory()
            ->createAvailabilityHandler()
            ->updateAvailability($sku);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return void
     */
    public function updateAvailabilityForStore($sku, StoreTransfer $storeTransfer)
    {
        $this->getFactory()
            ->createAvailabilityHandler()
            ->updateAvailabilityForStore($sku, $storeTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductAbstract
     * @param int $idLocale
     *
     * @return \Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer
     */
    public function getProductAbstractAvailability($idProductAbstract, $idLocale)
    {
        return $this->getFactory()
            ->createProductReservationReader()
            ->getProductAbstractAvailability($idProductAbstract, $idLocale);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductAbstract
     * @param int $idLocale
     * @param int $idStore
     *
     * @return \Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer|null
     */
    public function findProductAbstractAvailability($idProductAbstract, $idLocale, $idStore)
    {
        return $this->getFactory()
            ->createProductReservationReader()
            ->findProductAbstractAvailability($idProductAbstract, $idLocale, $idStore);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteAvailabilityRequestTransfer $productConcreteAvailabilityRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer|null
     */
    public function findProductConcreteAvailability(
        ProductConcreteAvailabilityRequestTransfer $productConcreteAvailabilityRequestTransfer
    ) {
        return $this->getFactory()
            ->createProductReservationReader()
            ->findProductConcreteAvailability($productConcreteAvailabilityRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idAvailabilityAbstract
     *
     * @return void
     */
    public function touchAvailabilityAbstract($idAvailabilityAbstract)
    {
        $this->getFactory()
            ->createAvailabilityHandler()
            ->touchAvailabilityAbstract($idAvailabilityAbstract);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use saveProductAvailabilityForStore() instead.
     *
     * @param string $sku
     * @param \Spryker\DecimalObject\Decimal $quantity
     *
     * @return int
     */
    public function saveProductAvailability(string $sku, Decimal $quantity): int
    {
        return $this->getFactory()
            ->createAvailabilityHandler()
            ->saveCurrentAvailability($sku, $quantity);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $sku
     * @param \Spryker\DecimalObject\Decimal $quantity
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return int
     */
    public function saveProductAvailabilityForStore(string $sku, Decimal $quantity, StoreTransfer $storeTransfer): int
    {
        return $this->getFactory()
            ->createAvailabilityHandler()
            ->saveCurrentAvailabilityForStore($sku, $quantity, $storeTransfer);
    }
}
