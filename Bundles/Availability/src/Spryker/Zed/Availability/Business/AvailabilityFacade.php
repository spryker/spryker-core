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
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Availability\Business\AvailabilityBusinessFactory getFactory()
 */
class AvailabilityFacade extends AbstractFacade implements AvailabilityFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @deprecated Use isProductSellableForStore() instead.
     *
     * @param string $sku
     * @param int $quantity
     *
     * @return bool
     */
    public function isProductSellable($sku, $quantity)
    {
        return $this->getFactory()
            ->createSellableModel()
            ->isProductSellable($sku, $quantity);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $sku
     * @param int $quantity
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return bool
     */
    public function isProductSellableForStore($sku, $quantity, StoreTransfer $storeTransfer)
    {
        return $this->getFactory()
            ->createSellableModel()
            ->isProductSellableForStore($sku, $quantity, $storeTransfer);
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
     *
     * @api
     *
     * @deprecated Use calculateStockForProductWithStore() instead.
     *
     * @param string $sku
     *
     * @return int
     */
    public function calculateStockForProduct($sku)
    {
        return $this->getFactory()
            ->createSellableModel()
            ->calculateStockForProduct($sku);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return int
     */
    public function calculateStockForProductWithStore($sku, StoreTransfer $storeTransfer)
    {
        return $this->getFactory()
            ->createSellableModel()
            ->calculateStockForProductWithStore($sku, $storeTransfer);
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
     *
     * @api
     *
     * @deprecated Use saveProductAvailabilityForStore() instead.
     *
     * @param string $sku
     * @param int $quantity
     *
     * @return int
     */
    public function saveProductAvailability($sku, $quantity)
    {
        return $this->getFactory()
            ->createAvailabilityHandler()
            ->saveCurrentAvailability($sku, $quantity);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $sku
     * @param int $quantity
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return int
     */
    public function saveProductAvailabilityForStore($sku, $quantity, StoreTransfer $storeTransfer)
    {
        return $this->getFactory()
            ->createAvailabilityHandler()
            ->saveCurrentAvailabilityForStore($sku, $quantity, $storeTransfer);
    }
}
