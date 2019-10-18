<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Availability\Business;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer;
use Generated\Shared\Transfer\ProductConcreteAvailabilityRequestTransfer;
use Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\DecimalObject\Decimal;

interface AvailabilityFacadeInterface
{
    /**
     * Specification:
     *  - Checks if product is never out of stock for given store.
     *  - Checks if product has stock in stock table.
     *  - Checks if have placed orders where items have state machine state flagged as reserved.
     *
     * @api
     *
     * @param string $sku
     * @param \Spryker\DecimalObject\Decimal $quantity
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return bool
     */
    public function isProductSellableForStore(string $sku, Decimal $quantity, StoreTransfer $storeTransfer): bool;

    /**
     * Specification:
     *  - Checks if product is available.
     *
     * @api
     *
     * @param int $idProductConcrete
     *
     * @return bool
     */
    public function isProductConcreteAvailable(int $idProductConcrete): bool;

    /**
     * Specification:
     *  - Returns calculated availability value which is product stock minus reserved state machine items quantities.
     *
     * @api
     *
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    public function calculateAvailabilityForProductWithStore(string $sku, StoreTransfer $storeTransfer): Decimal;

    /**
     * Specification:
     *  - Checkout PreCondition plugin call, check if all items in cart is sellable.
     *  - Writes error message into CheckoutResponseTransfer.
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
    );

    /**
     * Specification:
     *  - Calculates current item availability, take into account reserved items
     *  - Stores new availability for concrete product
     *  - Stores sum of all concrete product availability for abstract product
     *  - Touches availability abstract collector if data changed
     *
     * @api
     *
     * @param string $sku
     *
     * @return void
     */
    public function updateAvailability($sku);

    /**
     * Specification:
     *  - Calculates current item availability, for given store take into account reserved items
     *  - Stores availability for concrete product
     *  - Stores sum of all concrete product availability for abstract product
     *  - Touches availability abstract collector if data changed
     *
     * @api
     *
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return void
     */
    public function updateAvailabilityForStore($sku, StoreTransfer $storeTransfer);

    /**
     * Specification:
     *  - Reads product availability data from persistence, stock, reservation, availability.
     *  - Returns data for selected abstract product.
     *
     * @api
     *
     * @deprecated Use `AvailabilityFacadeInterface::findProductAbstractAvailabilityBySkuForStore() instead`.
     *
     * @param int $idProductAbstract
     * @param int $idLocale
     *
     * @return \Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer
     */
    public function getProductAbstractAvailability($idProductAbstract, $idLocale);

    /**
     * Specification:
     *  - Reads product availability data from persistence, stock, reservation, availability.
     *  - Returns data for selected abstract product.
     *
     * @api
     *
     * @deprecated Use `AvailabilityFacadeInterface::findProductAbstractAvailabilityBySkuForStore() instead`.
     *
     * @param int $idProductAbstract
     * @param int $idLocale
     * @param int $idStore
     *
     * @return \Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer|null
     */
    public function findProductAbstractAvailability($idProductAbstract, $idLocale, $idStore);

    /**
     * Specification:
     *  - Finds product concrete availability as is stored in persistence.
     *
     * @api
     *
     * @deprecated Use `AvailabilityFacadeInterface::findProductConcreteAvailabilityBySkuForStore() instead`.
     *
     * @param \Generated\Shared\Transfer\ProductConcreteAvailabilityRequestTransfer $productConcreteAvailabilityRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer|null
     */
    public function findProductConcreteAvailability(ProductConcreteAvailabilityRequestTransfer $productConcreteAvailabilityRequestTransfer);

    /**
     * Specification:
     *  - Finds product abstract availability as is stored in persistence.
     *  - If nothing was stored in persistence, abstract availability gets calculated and stored.
     *
     * @api
     *
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer|null
     */
    public function findProductAbstractAvailabilityBySkuForStore(string $sku, StoreTransfer $storeTransfer): ?ProductAbstractAvailabilityTransfer;

    /**
     * Specification:
     *  - Finds product concrete availability as is stored in persistence.
     *
     * @api
     *
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer|null
     */
    public function findProductConcreteAvailabilityBySkuForStore(string $sku, StoreTransfer $storeTransfer): ?ProductConcreteAvailabilityTransfer;

    /**
     * Specification:
     *  - Touches availability abstract collector for given abstract product
     *
     * @api
     *
     * @param int $idAvailabilityAbstract
     *
     * @return void
     */
    public function touchAvailabilityAbstract($idAvailabilityAbstract);

    /**
     * Specification:
     *  - Updates availability for given concrete sku, by quantity.
     *  - Doesn't update the flag `isNeverOutOfStock`.
     *  - Touches availability collector if data changed
     *  - Returns id of availability abstract
     *
     * @api
     *
     * @param string $sku
     * @param \Spryker\DecimalObject\Decimal $quantity
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return int
     */
    public function saveProductAvailabilityForStore(string $sku, Decimal $quantity, StoreTransfer $storeTransfer): int;
}
