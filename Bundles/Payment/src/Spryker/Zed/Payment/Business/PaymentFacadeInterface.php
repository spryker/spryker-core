<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Business;

use Generated\Shared\Transfer\AddPaymentMethodTransfer;
use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\DeletePaymentMethodTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PaymentMethodAddedTransfer;
use Generated\Shared\Transfer\PaymentMethodCollectionRequestTransfer;
use Generated\Shared\Transfer\PaymentMethodCollectionResponseTransfer;
use Generated\Shared\Transfer\PaymentMethodCollectionTransfer;
use Generated\Shared\Transfer\PaymentMethodCriteriaTransfer;
use Generated\Shared\Transfer\PaymentMethodDeletedTransfer;
use Generated\Shared\Transfer\PaymentMethodResponseTransfer;
use Generated\Shared\Transfer\PaymentMethodsTransfer;
use Generated\Shared\Transfer\PaymentMethodTransfer;
use Generated\Shared\Transfer\PaymentProviderCollectionRequestTransfer;
use Generated\Shared\Transfer\PaymentProviderCollectionResponseTransfer;
use Generated\Shared\Transfer\PaymentProviderCollectionTransfer;
use Generated\Shared\Transfer\PaymentProviderCriteriaTransfer;
use Generated\Shared\Transfer\PaymentProviderResponseTransfer;
use Generated\Shared\Transfer\PaymentProviderTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SalesPaymentTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;

/**
 * @method \Spryker\Zed\Payment\Business\PaymentBusinessFactory getFactory()
 */
interface PaymentFacadeInterface
{
    /**
     * Specification:
     * - Finds available payment methods
     * - Finds payment methods with `is_hidden` set to `false` (if such a column exists in the database).
     * - Runs filter plugins
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodsTransfer
     */
    public function getAvailableMethods(QuoteTransfer $quoteTransfer): PaymentMethodsTransfer;

    /**
     * Specification:
     * - Check whether the given order has a foreign payment selection key.
     * - Terminates payment authorization if not.
     * - Receives all the necessary information about the foreign payment method.
     * - Terminates payment authorization if the payment method is not found or no `paymentAuthorizationEndpoint` is specified for it.
     * - Uses `PaymentAuthorizeRequestExpanderPluginInterface` plugins stack to expand payment authorization request data.
     * - Enhances the payment authorization request data by including the tenant identifier value if it is provided.
     * - Sends an HTTP request with all pre-selected quote fields using URL from `PaymentMethod.paymentAuthorizationEndpoint`.
     * - Updates CheckoutResponseTransfer with errors or the redirect URL according to response received.
     * - The redirect URL can be prefixed with a custom URL from {@link PaymentConfig::getStoreFrontPaymentPage()},
     * in this case redirect URL will be added as base64-encoded GET parameter `url`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    public function initForeignPaymentForCheckoutProcess(
        QuoteTransfer $quoteTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer
    ): void;

    /**
     * Specification:
     * - Used to support only foreign payment methods.
     * - Requires `AddPaymentMethod.labelName` transfer field to be set.
     * - Requires `AddPaymentMethod.groupName` transfer field to be set.
     * - Requires `AddPaymentMethod.messageAttributes.storeReference` to be set
     * - Creates payment provider if respective provider doesn't exist in the database.
     * - Creates payment method if the payment method with provided key doesn't exist in the database.
     * - Updates payment method if the payment method with provided key exist in the database.
     * - Sets payment method `is_hidden` flag to `false`.
     * - Checks if there's a `AddPaymentMethod.messageAttributes.timestamp` and proceed with action only if it's null or newer than `last_message_timestamp`.
     * - Returns `PaymentMethod` transfer filled with payment method data.
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\Payment\Business\PaymentFacadeInterface::addPaymentMethod()} instead.
     *
     * @param \Generated\Shared\Transfer\AddPaymentMethodTransfer|\Generated\Shared\Transfer\PaymentMethodAddedTransfer $addPaymentMethodTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodTransfer
     */
    public function enableForeignPaymentMethod(AddPaymentMethodTransfer|PaymentMethodAddedTransfer $addPaymentMethodTransfer): PaymentMethodTransfer;

    /**
     * Specification:
     * - Used to support only foreign payment methods.
     * - Requires `AddPaymentMethod.labelName` transfer field to be set.
     * - Requires `AddPaymentMethod.groupName` transfer field to be set.
     * - Creates payment provider if respective provider doesn't exist in the database.
     * - Creates payment method if the payment method with provided key doesn't exist in the database.
     * - Updates payment method if the payment method with provided key exist in the database.
     * - Sets payment method `is_hidden` flag to `false`.
     * - Checks if there's a `AddPaymentMethod.messageAttributes.timestamp` and proceed with action only if it's null or newer than `last_message_timestamp`.
     * - Returns `PaymentMethod` transfer filled with payment method data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AddPaymentMethodTransfer|\Generated\Shared\Transfer\PaymentMethodAddedTransfer $addPaymentMethodTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodTransfer
     */
    public function addPaymentMethod(AddPaymentMethodTransfer|PaymentMethodAddedTransfer $addPaymentMethodTransfer): PaymentMethodTransfer;

    /**
     * Specification:
     * - Used to support only foreign payment methods.
     * - Requires `DeletePaymentMethod.labelName` transfer field to be set.
     * - Requires `DeletePaymentMethod.groupName` transfer field to be set.
     * - Requires `DeletePaymentMethod.messageAttributes.storeReference` to be set
     * - Uses the specified data to find a payment method.
     * - Sets payment method `is_hidden` flag to `true` (if it exists in the database).
     * - Creates hidden payment method if its provided key doesn't exist in the database.
     * - Checks if there's a `DeletePaymentMethod.messageAttributes.timestamp` and proceed with action only if it's null or newer than `last_message_timestamp`.
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\Payment\Business\PaymentFacadeInterface::deletePaymentMethod()} instead.
     *
     * @param \Generated\Shared\Transfer\DeletePaymentMethodTransfer|\Generated\Shared\Transfer\PaymentMethodDeletedTransfer $deletePaymentMethodTransfer
     *
     * @return void
     */
    public function disableForeignPaymentMethod(DeletePaymentMethodTransfer|PaymentMethodDeletedTransfer $deletePaymentMethodTransfer): void;

    /**
     * Specification:
     * - Used to support only foreign payment methods.
     * - Requires `DeletePaymentMethod.labelName` transfer field to be set.
     * - Requires `DeletePaymentMethod.groupName` transfer field to be set.
     * - Uses the specified data to find a payment method.
     * - Sets payment method `is_hidden` flag to `true` (if it exists in the database).
     * - Creates hidden payment method if its provided key doesn't exist in the database.
     * - Checks if there's a `DeletePaymentMethod.messageAttributes.timestamp` and proceed with action only if it's null or newer than `last_message_timestamp`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DeletePaymentMethodTransfer|\Generated\Shared\Transfer\PaymentMethodDeletedTransfer $deletePaymentMethodTransfer
     *
     * @return void
     */
    public function deletePaymentMethod(DeletePaymentMethodTransfer|PaymentMethodDeletedTransfer $deletePaymentMethodTransfer): void;

    /**
     * Specification:
     * - Distributes total price to payment methods
     * - Calculates price to pay
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function recalculatePayments(CalculableObjectTransfer $calculableObjectTransfer): void;

    /**
     * Specification:
     * - Finds payment providers which has available payment methods for the given store.
     *
     * @api
     *
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\PaymentProviderCollectionTransfer
     */
    public function getAvailablePaymentProvidersForStore(string $storeName): PaymentProviderCollectionTransfer;

    /**
     * Specification:
     * - Finds payment method by the provided id.
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\Payment\Business\PaymentFacadeInterface::getPaymentMethodCollection()} instead.
     *
     * @param int $idPaymentMethod
     *
     * @return \Generated\Shared\Transfer\PaymentMethodResponseTransfer
     */
    public function findPaymentMethodById(int $idPaymentMethod): PaymentMethodResponseTransfer;

    /**
     * Specification:
     * - Updates payment method in database using provided PaymentMethod transfer object data.
     * - Updates or creates payment method store relations using 'storeRelation' collection in the PaymentMethod transfer object.
     * - Returns PaymentMethodResponse transfer object.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodResponseTransfer
     */
    public function updatePaymentMethod(
        PaymentMethodTransfer $paymentMethodTransfer
    ): PaymentMethodResponseTransfer;

    /**
     * Specification:
     * - Checks if selected payment methods exist.
     * - Checks `QuoteTransfer.payments` and `QuoteTransfer.payment` for BC reasons.
     * - Returns `false` and add an error in case at least one of the payment methods
     *  does not exist or is not available for `QuoteTransfer`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function isQuotePaymentMethodValid(
        QuoteTransfer $quoteTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer
    ): bool;

    /**
     * Specification:
     * - Creates payment provider.
     * - Requires PaymentProviderTransfer.paymentProviderKey.
     * - Requires PaymentProviderTransfer.name.
     * - Creates payment methods if PaymentProviderTransfer.paymentMethods are provided.
     * - Requires PaymentMethodTransfer.paymentMethodKey.
     * - Requires PaymentMethodTransfer.name.
     * - Creates payment method store relations if PaymentMethodTransfer.storeRelation is provided.
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\Payment\Business\PaymentFacadeInterface::createPaymentProviderCollection()} instead.
     *
     * @param \Generated\Shared\Transfer\PaymentProviderTransfer $paymentProviderTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentProviderResponseTransfer
     */
    public function createPaymentProvider(PaymentProviderTransfer $paymentProviderTransfer): PaymentProviderResponseTransfer;

    /**
     * Specification:
     * - Creates payment method.
     * - Requires PaymentMethodTransfer.idPaymentProvider.
     * - Requires PaymentMethodTransfer.paymentMethodKey.
     * - Requires PaymentMethodTransfer.name.
     * - Creates payment method store relations if PaymentMethodTransfer.storeRelation is provided.
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\Payment\Business\PaymentFacadeInterface::createPaymentMethodCollection()} instead.
     *
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodResponseTransfer
     */
    public function createPaymentMethod(PaymentMethodTransfer $paymentMethodTransfer): PaymentMethodResponseTransfer;

    /**
     * Specification:
     * - Deactivates payment method.
     * - Requires PaymentMethodTransfer.idPaymentMethod.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodResponseTransfer
     */
    public function deactivatePaymentMethod(PaymentMethodTransfer $paymentMethodTransfer): PaymentMethodResponseTransfer;

    /**
     * Specification:
     * - Activates payment method.
     * - Requires PaymentMethodTransfer.idPaymentMethod.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodResponseTransfer
     */
    public function activatePaymentMethod(PaymentMethodTransfer $paymentMethodTransfer): PaymentMethodResponseTransfer;

    /**
     * Specification:
     * - Runs pre-check plugins
     *
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function checkoutPreCheck(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer): bool;

    /**
     * Specification:
     * - Runs post-check plugins
     *
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    public function checkoutPostCheck(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer): void;

    /**
     * Specification:
     *  - Returns payment method price to pay
     *
     * @api
     *
     * @deprecated Use QuoteTransfer.payments or OrderTransfer.payments instead to get amount per payment method.
     *
     * @param \Generated\Shared\Transfer\SalesPaymentTransfer $salesPaymentTransfer
     *
     * @return int
     */
    public function getPaymentMethodPriceToPay(SalesPaymentTransfer $salesPaymentTransfer): int;

    /**
     * Specification:
     *  - Populates order transfer with payment data
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\SalesPayment\Business\SalesPaymentFacadeInterface::expandOrderWithPayments()} instead.
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function hydrateOrderPayments(OrderTransfer $orderTransfer): OrderTransfer;

    /**
     * Specification:
     * - Requires PaymentProviderTransfer.paymentProviderKey transfer field to be set.
     * - Returns a payment provider transfer found using PaymentProvider transfer.
     * - Returns NULL if payment provider is not found.
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\Payment\Business\PaymentFacadeInterface::getPaymentProviderCollection()} instead.
     *
     * @param \Generated\Shared\Transfer\PaymentProviderTransfer $paymentProviderTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentProviderTransfer|null
     */
    public function findPaymentProvider(PaymentProviderTransfer $paymentProviderTransfer): ?PaymentProviderTransfer;

    /**
     * Specification:
     * - Creates sales payments
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\SalesPayment\Business\SalesPaymentFacadeInterface::saveOrderPayments()} instead.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    public function savePaymentForCheckout(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse): void;

    /**
     * Specification:
     * - Uses OrderTransfer.orderReference, OrderTransfer.currencyIsoCode and order item ids to send the event.
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\SalesPayment\Business\SalesPaymentFacade::sendEventPaymentCancelReservationPending()} instead.
     *
     * @param array<int> $orderItemIds
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function sendEventPaymentCancelReservationPending(array $orderItemIds, OrderTransfer $orderTransfer): void;

    /**
     * Specification:
     * - Sends event if total count of order items above zero.
     * - Uses orderTransfer.orderReference, orderTransfer.currencyIsoCode, order item ids and total count to send the event.
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\SalesPayment\Business\SalesPaymentFacade::sendEventPaymentConfirmationPending()} instead.
     *
     * @param array<int> $orderItemIds
     * @param int $orderItemsTotal
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function sendEventPaymentConfirmationPending(
        array $orderItemIds,
        int $orderItemsTotal,
        OrderTransfer $orderTransfer
    ): void;

    /**
     * Specification:
     * - Sends event if total count of order items above zero.
     * - Uses orderTransfer.orderReference, orderTransfer.currencyIsoCode, order item ids and total count to send the event.
     * - Total items count will be a negative number.
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\SalesPayment\Business\SalesPaymentFacade::sendEventPaymentRefundPending()} instead.
     *
     * @param array<int> $orderItemIds
     * @param int $orderItemsTotal
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function sendEventPaymentRefundPending(
        array $orderItemIds,
        int $orderItemsTotal,
        OrderTransfer $orderTransfer
    ): void;

    /**
     * Specification:
     * - Finds the appropriate event for the current transfer using `PaymentConfig::getSupportedOrderPaymentEventTransfersList()`.
     * - If nothing is found - throws `InvalidPaymentEventException`.
     * - Otherwise triggers the found OMS event for all order items from `$orderPaymentEventTransfer::getOrderItemIds()`.
     * - The `$orderPaymentEventTransfer` parameter is a request transfer as provided by order payment event (e.g. PaymentCancellationFailedTransfer).
     *
     * @api
     *
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $orderPaymentEventTransfer
     *
     * @return void
     */
    public function triggerPaymentMessageOmsEvent(TransferInterface $orderPaymentEventTransfer): void;

    /**
     * Specification:
     * - Returns a collection of payment providers by specified criteria.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PaymentProviderCriteriaTransfer $paymentProviderCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentProviderCollectionTransfer
     */
    public function getPaymentProviderCollection(PaymentProviderCriteriaTransfer $paymentProviderCriteriaTransfer): PaymentProviderCollectionTransfer;

    /**
     * Specification:
     * - Returns a collection of payment methods by specified criteria.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PaymentMethodCriteriaTransfer $paymentMethodCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodCollectionTransfer
     */
    public function getPaymentMethodCollection(PaymentMethodCriteriaTransfer $paymentMethodCriteriaTransfer): PaymentMethodCollectionTransfer;

    /**
     * Specification:
     * - Requires `PaymentMethodCollectionRequestTransfer.paymentProviders` to be set.
     * - Requires `PaymentProviderCollectionRequestTransfer.isTransactional` to be set.
     * - Requires `PaymentProviderTransfer.paymentProviderKey` to be set for each element of `PaymentProviderCollectionRequestTransfer.paymentProviders`.
     * - Requires `PaymentProviderTransfer.name` to be set for each element of `PaymentProviderCollectionRequestTransfer.paymentProviders`.
     * - Requires `PaymentMethodTransfer.paymentMethodKey` to be set for each element of `PaymentProviderCollectionRequestTransfer.paymentProvider.paymentMethods`.
     * - Requires `PaymentMethodTransfer.name` to be set for each element of `PaymentProviderCollectionRequestTransfer.paymentProvider.paymentMethods`.
     * - Creates a collection of payment providers.
     * - Creates a collection of payment methods if `PaymentProviderCollectionRequestTransfer.paymentProvider.paymentMethods` is provided.
     * - Payment provider key is used as identifier at `ErrorTransfer.entityIdentifier`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PaymentProviderCollectionRequestTransfer $paymentProviderCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentProviderCollectionResponseTransfer
     */
    public function createPaymentProviderCollection(
        PaymentProviderCollectionRequestTransfer $paymentProviderCollectionRequestTransfer
    ): PaymentProviderCollectionResponseTransfer;

    /**
     * Specification:
     * - Requires `PaymentMethodCollectionRequestTransfer.paymentMethods` to be set.
     * - Requires `PaymentMethodCollectionRequestTransfer.isTransactional` to be set.
     * - Requires `PaymentMethodTransfer.paymentMethodKey` to be set for each element of `PaymentMethodCollectionRequestTransfer.paymentMethods`.
     * - Requires `PaymentMethodTransfer.name` to be set for each element of `PaymentMethodCollectionRequestTransfer.paymentMethods`.
     * - Requires `PaymentMethodTransfer.idPaymentProvider` to be set for each element of `PaymentMethodCollectionRequestTransfer.paymentMethods`.
     * - Creates a collection of payment methods.
     * - Payment method key is used as identifier at `ErrorTransfer.entityIdentifier`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PaymentMethodCollectionRequestTransfer $paymentMethodCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodCollectionResponseTransfer
     */
    public function createPaymentMethodCollection(
        PaymentMethodCollectionRequestTransfer $paymentMethodCollectionRequestTransfer
    ): PaymentMethodCollectionResponseTransfer;

    /**
     * Specification:
     * - Requires `PaymentTransfer.paymentMethod` to be set.
     * - Requires `PaymentTransfer.paymentProvider` to be set.
     * - Requires `StoreTransfer.idStore` or `StoreTransfer.name` to be set.
     * - Expands PaymentTransfer with paymentSelection field if it was not populated before.
     * - Uses provided arguments to retrieve payment method from database.
     * - Sets `PaymentTransfer.paymentSelection` to payment method key or payment method key with added suffix for foreign payment methods.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentTransfer
     */
    public function expandPaymentWithPaymentSelection(PaymentTransfer $paymentTransfer, StoreTransfer $storeTransfer): PaymentTransfer;

    /**
     * Specification:
     * - Requires `paymentProvider` to be set.
     * - Requires `paymentMethod` to be set.
     * - Returns the payment method key.
     *
     * @api
     *
     * @param string $paymentProvider
     * @param string $paymentMethod
     *
     * @return string
     */
    public function generatePaymentMethodKey(string $paymentProvider, string $paymentMethod): string;
}
