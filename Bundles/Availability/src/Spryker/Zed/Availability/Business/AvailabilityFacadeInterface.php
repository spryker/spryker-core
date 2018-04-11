<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Availability\Business;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\OfferTransfer;
use Generated\Shared\Transfer\ProductConcreteAvailabilityRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreTransfer;

interface AvailabilityFacadeInterface
{
    /**
     * Specification:
     *  - Checks if product is never out of stock for current store.
     *  - Checks if product has stock in stock table.
     *  - Checks if have placed orders where items have state machine state flagged as reserved.
     *
     * @api
     *
     * @param string $sku
     * @param int $quantity
     *
     * @return bool
     */
    public function isProductSellable($sku, $quantity);

    /**
     * Specification:
     *  - Checks if product is never out of stock for given store.
     *  - Checks if product has stock in stock table.
     *  - Checks if have placed orders where items have state machine state flagged as reserved.
     *
     * @api
     *
     * @param string $sku
     * @param int $quantity
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return bool
     */
    public function isProductSellableForStore($sku, $quantity, StoreTransfer $storeTransfer);

    /**
     * Specification:
     *  - Checks if product has stock in stock table for current store.
     *  - Checks if have placed orders where items have state machine state flagged as reserved.
     *  - Returns integer value which is Product stock - reserved state machine items.
     *
     * @api
     *
     * @param string $sku
     *
     * @return int
     */
    public function calculateStockForProduct($sku);

    /**
     * Specification:
     *  - Checks if product has stock in stock table for current store.
     *  - Checks if have placed orders where items have state machine state flagged as reserved.
     *  - Returns integer value which is Product stock - reserved state machine items.
     *
     * @api
     *
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return int
     */
    public function calculateStockForProductWithStore($sku, StoreTransfer $storeTransfer);

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
     *  - Calculates current item stock, take into account reserved items
     *  - Stores new stock for concrete product
     *  - Stores sum of all concrete product stocks for abstract product
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
     *  - Calculates current item stock, for given store take into account reserved items
     *  - Stores new stock for concrete product
     *  - Stores sum of all concrete product stocks for abstract product
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
     * @param \Generated\Shared\Transfer\ProductConcreteAvailabilityRequestTransfer $productConcreteAvailabilityRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer|null
     */
    public function findProductConcreteAvailability(ProductConcreteAvailabilityRequestTransfer $productConcreteAvailabilityRequestTransfer);

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
     *
     * Specification:
     *  - Updates availability for given sku, by quantity for current store
     *  - Touches availability collector if data changed
     *  - Returns id of availability abstract
     *
     * @api
     *
     * @param string $sku
     * @param int $quantity
     *
     * @return int
     */
    public function saveProductAvailability($sku, $quantity);

    /**
     * Specification:
     *  - Updates availability for given sku, by quantity
     *  - Touches availability collector if data changed
     *  - Returns id of availability abstract
     *
     * @api
     *
     * @param string $sku
     * @param int $quantity
     * @param \Generated\Shared\Transfer\StoreTransfer|null $storeTransfer
     *
     * @return int
     */
    public function saveProductAvailabilityForStore($sku, $quantity, StoreTransfer $storeTransfer);

    /**
     * Specification:
     *  - Add information about quote item stock to an offer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OfferTransfer $offerTransfer
     *
     * @return \Generated\Shared\Transfer\OfferTransfer
     */
    public function hydrateOfferWithQuoteItemStock(OfferTransfer $offerTransfer): OfferTransfer;
}
