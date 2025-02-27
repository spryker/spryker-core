<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard\Business;

use ArrayObject;
use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\CollectedDiscountTransfer;
use Generated\Shared\Transfer\GiftCardTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\PaymentGiftCardCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\PaymentMethodsTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SalesOrderItemGiftCardCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\SalesOrderItemGiftCardCollectionResponseTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Generated\Shared\Transfer\ShipmentGroupTransfer;

interface GiftCardFacadeInterface
{
    /**
     * Specification:
     * - Issues a gift card with provided configuration
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\GiftCardTransfer $giftCardTransfer
     *
     * @return \Generated\Shared\Transfer\GiftCardTransfer
     */
    public function create(GiftCardTransfer $giftCardTransfer);

    /**
     * Specification:
     * - Finds a gift card by ID
     * - Hydrates a gift card transfer
     *
     * @api
     *
     * @param int $idGiftCard
     *
     * @return \Generated\Shared\Transfer\GiftCardTransfer|null
     */
    public function findById($idGiftCard);

    /**
     * Specification:
     * - Loops by order items and finds abstract and concrete gift card configurations
     * - Adds gift card meta transfer to cart change items
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandGiftCardMetadata(CartChangeTransfer $cartChangeTransfer);

    /**
     * Specification:
     * - Removes gift cards from discountable items (GC are not discountable)
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CollectedDiscountTransfer $collectedDiscountTransfer
     *
     * @return \Generated\Shared\Transfer\CollectedDiscountTransfer
     */
    public function filterGiftCardDiscountableItems(CollectedDiscountTransfer $collectedDiscountTransfer);

    /**
     * Specification:
     * - Filters available payment methods by gift card black list
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PaymentMethodsTransfer $paymentMethodsTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodsTransfer
     */
    public function filterPaymentMethods(PaymentMethodsTransfer $paymentMethodsTransfer, QuoteTransfer $quoteTransfer);

    /**
     * Specification:
     * - Distinguish applicable and non-applicable gift cards
     * - Creates payment methods for applicable gift cards
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function recalculate(CalculableObjectTransfer $calculableObjectTransfer);

    /**
     * Specification:
     * - Checks is a gift card already in use
     * - Checks that a gift card payment method amount is not more than a rest of gift card value
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return bool
     */
    public function precheckSalesOrderGiftCards(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse);

    /**
     * Specification:
     * - Creates gift card payments.
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\GiftCard\Business\GiftCardFacadeInterface::saveOrderGiftCards()} instead.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    public function saveGiftCardPayments(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse);

    /**
     * Specification:
     * - Persists gift card's data from order items into DB table spy_sales_order_item_gift_card.
     * - Saves gift card payments from the quote into DB table spy_payment_gift_card.
     * - Executes GiftCardPaymentSaverPluginInterface plugin stack.
     *
     * @api
     *
     * @deprecated Partially replaced by {@link \Spryker\Zed\GiftCard\Business\GiftCardFacadeInterface::createGiftCardPaymentsFromQuote()}.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function saveOrderGiftCards(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer): void;

    /**
     * Specification:
     * - Checks an order item on Gift Card meta data
     * - Return false if there is no gift card meta data for the order item
     *
     * @api
     *
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    public function isGiftCardOrderItem($idSalesOrderItem);

    /**
     * Specification:
     * - Finds a gift card configuration for an order item
     * - Creates a gift card meta data record (spy_sales_order_item_gift_card)
     * - Creates a gift card based on the meta data (generates code, sets a value)
     *
     * @api
     *
     * @param int $idSalesOrderItem
     *
     * @return \Generated\Shared\Transfer\GiftCardTransfer
     */
    public function createGiftCardForOrderItem($idSalesOrderItem);

    /**
     * Specification:
     * - Persists gift card's items from quote.
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\GiftCard\Business\GiftCardFacadeInterface::saveOrderGiftCards()} instead.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    public function saveSalesOrderGiftCardItems(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse);

    /**
     * Specification:
     * - Checks usages for a gift card code
     * - Returns `false`, if a gift card is never used
     * - Otherwise returns `true`
     *
     * @api
     *
     * @param string $code
     *
     * @return bool
     */
    public function isUsed($code);

    /**
     * Specification:
     * - Provides a replacement strategy for a gift card usage
     * - Generates a next gift card with the rest of the current gift card amount
     *
     * @api
     *
     * @param int $idSalesOrder
     *
     * @return void
     */
    public function replaceGiftCards($idSalesOrder);

    /**
     * Specification:
     * - Filters non-available for gift cards shipment methods.
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\GiftCard\Business\GiftCardFacadeInterface::filterShipmentGroupMethods()} instead.
     *
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ShipmentMethodTransfer> $shipmentMethods
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ShipmentMethodTransfer>
     */
    public function filterShipmentMethods(ArrayObject $shipmentMethods, QuoteTransfer $quoteTransfer);

    /**
     * Specification:
     * - Filters non-available for gift cards shipment methods for each shipment group.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ShipmentMethodTransfer>
     */
    public function filterShipmentGroupMethods(ShipmentGroupTransfer $shipmentGroupTransfer): ArrayObject;

    /**
     * Specification:
     * - Finds gift card payments by the order id
     * - Finds used gift cards by related gift card payment codes
     *
     * @api
     *
     * @param int $idSalesOrder
     *
     * @return array<\Generated\Shared\Transfer\GiftCardTransfer>
     */
    public function findGiftCardsByIdSalesOrder($idSalesOrder);

    /**
     * Specification:
     * - Finds an gift card entity
     * - Maps it to a transfer object with meta information
     *
     * @api
     *
     * @param int $idSalesOrderItem
     *
     * @return \Generated\Shared\Transfer\GiftCardTransfer|null
     */
    public function findGiftCardByIdSalesOrderItem($idSalesOrderItem);

    /**
     * Specification:
     * - Sanitizes shipment in the shipment group collection which contains gift cards only.
     *
     * @api
     *
     * @param iterable<\Generated\Shared\Transfer\ShipmentGroupTransfer> $shipmentGroupCollection
     *
     * @return iterable<\Generated\Shared\Transfer\ShipmentGroupTransfer>
     */
    public function sanitizeShipmentGroupCollection(iterable $shipmentGroupCollection): iterable;

    /**
     * Specification:
     * - Sets gift card to the quote if the code hasn't been added already.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $cartCode
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addCartCode(QuoteTransfer $quoteTransfer, string $cartCode): QuoteTransfer;

    /**
     * Specification:
     * - Removes matching applied gift card and gift card payment from quote.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $cartCode
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function removeCartCode(QuoteTransfer $quoteTransfer, string $cartCode): QuoteTransfer;

    /**
     * Specification:
     * - Clears all gift cards and gift card payments from the quote.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function clearCartCodes(QuoteTransfer $quoteTransfer): QuoteTransfer;

    /**
     * Specification:
     * - Returns gift card apply success message in case the given gift card code has been applied successfully.
     * - Returns gift card apply failed message in case the given gift card code hasn't been applied successfully.
     * - Returns an empty failed message if code is not relevant.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $cartCode
     *
     * @return \Generated\Shared\Transfer\MessageTransfer|null
     */
    public function findOperationResponseMessage(QuoteTransfer $quoteTransfer, string $cartCode): ?MessageTransfer;

    /**
     * Specification:
     * - Returns payment map key based on `PaymentTransfer.paymentProvider`, `PaymentTransfer.paymentMethod` and `PaymentTransfer.giftCard.idGiftCard`.
     * - Requires `PaymentTransfer.paymentProvider`, `PaymentTransfer.paymentMethod` and `PaymentTransfer.giftCard.idGiftCard` to be set.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     *
     * @return string
     */
    public function buildPaymentMapKey(PaymentTransfer $paymentTransfer): string;

    /**
     * Specification:
     * - Iterates over `QuoteTransfer.payments` and saves gift card related payments into the `spy_payment_gift_card` DB table.
     * - Does not save a payment when `PaymentTransfer.giftCard` is not set.
     * - Does not save a payment when `PaymentTransfer.amount` is not set.
     * - Executes a stack of {@link \Spryker\Zed\GiftCard\Dependency\Plugin\GiftCardPaymentSaverPluginInterface} plugins.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function createGiftCardPaymentsFromQuote(
        QuoteTransfer $quoteTransfer,
        SaveOrderTransfer $saveOrderTransfer
    ): void;

    /**
     * Specification:
     * - Expects `PaymentGiftCardCollectionDeleteCriteriaTransfer.salesPaymentIds` to be provided.
     * - Removes payment gift card entities.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PaymentGiftCardCollectionDeleteCriteriaTransfer $paymentGiftCardCollectionDeleteCriteriaTransfer
     *
     * @return void
     */
    public function deletePaymentGiftCardCollection(
        PaymentGiftCardCollectionDeleteCriteriaTransfer $paymentGiftCardCollectionDeleteCriteriaTransfer
    ): void;

    /**
     * Specification:
     * - Uses `SalesOrderItemGiftCardCollectionDeleteCriteriaTransfer.salesOrderItemIds` to filter sales order item gift card entities by the sales order item IDs.
     * - Deletes found by criteria sales order item gift card entities.
     * - Does nothing if no criteria properties are set.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SalesOrderItemGiftCardCollectionDeleteCriteriaTransfer $salesOrderItemGiftCardCollectionDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemGiftCardCollectionResponseTransfer
     */
    public function deleteSalesOrderItemGiftCardCollection(
        SalesOrderItemGiftCardCollectionDeleteCriteriaTransfer $salesOrderItemGiftCardCollectionDeleteCriteriaTransfer
    ): SalesOrderItemGiftCardCollectionResponseTransfer;
}
