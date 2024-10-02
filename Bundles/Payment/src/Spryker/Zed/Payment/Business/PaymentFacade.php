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
use Generated\Shared\Transfer\PreOrderPaymentRequestTransfer;
use Generated\Shared\Transfer\PreOrderPaymentResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SalesPaymentTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Payment\Business\PaymentBusinessFactory getFactory()
 * @method \Spryker\Zed\Payment\Persistence\PaymentEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\Payment\Persistence\PaymentRepositoryInterface getRepository()
 */
class PaymentFacade extends AbstractFacade implements PaymentFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodsTransfer
     */
    public function getAvailableMethods(QuoteTransfer $quoteTransfer): PaymentMethodsTransfer
    {
        return $this->getFactory()
            ->createPaymentMethodReader()
            ->getAvailableMethods($quoteTransfer);
    }

    /**
     * {@inheritDoc}
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
    ): void {
        $this->getFactory()
            ->createForeignPayment()
            ->initializePayment($quoteTransfer, $checkoutResponseTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PreOrderPaymentRequestTransfer $preOrderPaymentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PreOrderPaymentResponseTransfer
     */
    public function initializePreOrderPayment(
        PreOrderPaymentRequestTransfer $preOrderPaymentRequestTransfer
    ): PreOrderPaymentResponseTransfer {
        return $this->getFactory()->createForeignPayment()->initializePreOrderPayment($preOrderPaymentRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PreOrderPaymentRequestTransfer $preOrderPaymentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PreOrderPaymentResponseTransfer
     */
    public function cancelPreOrderPayment(
        PreOrderPaymentRequestTransfer $preOrderPaymentRequestTransfer
    ): PreOrderPaymentResponseTransfer {
        return $this->getFactory()->createForeignPayment()->cancelPreOrderPayment($preOrderPaymentRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    public function confirmPreOrderPayment(
        QuoteTransfer $quoteTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer
    ): void {
        $this->getFactory()
            ->createForeignPayment()
            ->confirmPreOrderPayment($quoteTransfer, $checkoutResponseTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\Payment\Business\PaymentFacade::addPaymentMethod()} instead.
     *
     * @param \Generated\Shared\Transfer\AddPaymentMethodTransfer|\Generated\Shared\Transfer\PaymentMethodAddedTransfer $addPaymentMethodTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodTransfer
     */
    public function enableForeignPaymentMethod(AddPaymentMethodTransfer|PaymentMethodAddedTransfer $addPaymentMethodTransfer): PaymentMethodTransfer
    {
        $addPaymentMethodTransfer = ($addPaymentMethodTransfer instanceof PaymentMethodAddedTransfer) ? ($this->createAddPaymentMethodTransfer())->fromArray($addPaymentMethodTransfer->toArray()) : $addPaymentMethodTransfer;

        return $this->getFactory()->createPaymentMethodUpdater()
            ->enableForeignPaymentMethod($addPaymentMethodTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AddPaymentMethodTransfer|\Generated\Shared\Transfer\PaymentMethodAddedTransfer $addPaymentMethodTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodTransfer
     */
    public function addPaymentMethod(AddPaymentMethodTransfer|PaymentMethodAddedTransfer $addPaymentMethodTransfer): PaymentMethodTransfer
    {
        $addPaymentMethodTransfer = ($addPaymentMethodTransfer instanceof PaymentMethodAddedTransfer) ? ($this->createAddPaymentMethodTransfer())->fromArray($addPaymentMethodTransfer->toArray()) : $addPaymentMethodTransfer;

        return $this->getFactory()->createPaymentMethodUpdater()
            ->addPaymentMethod($addPaymentMethodTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AddPaymentMethodTransfer|\Generated\Shared\Transfer\UpdatePaymentMethodTransfer|\Generated\Shared\Transfer\DeletePaymentMethodTransfer $messageTransfer
     *
     * @return void
     */
    public function consumePaymentMethodMessage(AbstractTransfer $messageTransfer): void
    {
        $this->getFactory()->createPaymentMessageConsumer()->consumePaymentMessage($messageTransfer);
    }

    /**
     * @deprecated Will be removed with next major. This is only an intermediate solution to support both transfer types in {@link \Spryker\Zed\Payment\Business\PaymentFacade::addPaymentMethod()}
     *
     * @return \Generated\Shared\Transfer\AddPaymentMethodTransfer
     */
    protected function createAddPaymentMethodTransfer(): AddPaymentMethodTransfer
    {
        return new AddPaymentMethodTransfer();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\Payment\Business\PaymentFacade::deletePaymentMethod()} instead.
     *
     * @param \Generated\Shared\Transfer\DeletePaymentMethodTransfer|\Generated\Shared\Transfer\PaymentMethodDeletedTransfer $deletePaymentMethodTransfer
     *
     * @return void
     */
    public function disableForeignPaymentMethod(DeletePaymentMethodTransfer|PaymentMethodDeletedTransfer $deletePaymentMethodTransfer): void
    {
        $deletePaymentMethodTransfer = ($deletePaymentMethodTransfer instanceof PaymentMethodDeletedTransfer) ? ($this->createDeletePaymentMethodTransfer())->fromArray($deletePaymentMethodTransfer->toArray()) : $deletePaymentMethodTransfer;

        $this->getFactory()->createPaymentMethodUpdater()
            ->disableForeignPaymentMethod($deletePaymentMethodTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DeletePaymentMethodTransfer|\Generated\Shared\Transfer\PaymentMethodDeletedTransfer $deletePaymentMethodTransfer
     *
     * @return void
     */
    public function deletePaymentMethod(DeletePaymentMethodTransfer|PaymentMethodDeletedTransfer $deletePaymentMethodTransfer): void
    {
        $deletePaymentMethodTransfer = ($deletePaymentMethodTransfer instanceof PaymentMethodDeletedTransfer) ? ($this->createDeletePaymentMethodTransfer())->fromArray($deletePaymentMethodTransfer->toArray()) : $deletePaymentMethodTransfer;

        $this->getFactory()->createPaymentMethodUpdater()
            ->deletePaymentMethod($deletePaymentMethodTransfer);
    }

    /**
     * @deprecated Will be removed with next major. This is only an intermediate solution to support both transfer types in {@link \Spryker\Zed\Payment\Business\PaymentFacade::deletePaymentMethod()}
     *
     * @return \Generated\Shared\Transfer\DeletePaymentMethodTransfer
     */
    protected function createDeletePaymentMethodTransfer(): DeletePaymentMethodTransfer
    {
        return new DeletePaymentMethodTransfer();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function recalculatePayments(CalculableObjectTransfer $calculableObjectTransfer): void
    {
        $this->getFactory()
            ->createPaymentCalculator()
            ->recalculatePayments($calculableObjectTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\PaymentProviderCollectionTransfer
     */
    public function getAvailablePaymentProvidersForStore(string $storeName): PaymentProviderCollectionTransfer
    {
        return $this->getRepository()->getAvailablePaymentProvidersForStore($storeName);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\Payment\Business\PaymentFacade::getPaymentMethodCollection()} instead.
     *
     * @param int $idPaymentMethod
     *
     * @return \Generated\Shared\Transfer\PaymentMethodResponseTransfer
     */
    public function findPaymentMethodById(int $idPaymentMethod): PaymentMethodResponseTransfer
    {
        return $this->getFactory()
            ->createPaymentMethodFinder()
            ->findPaymentMethodById($idPaymentMethod);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodResponseTransfer
     */
    public function updatePaymentMethod(
        PaymentMethodTransfer $paymentMethodTransfer
    ): PaymentMethodResponseTransfer {
        return $this->getFactory()
            ->createPaymentMethodUpdater()
            ->update($paymentMethodTransfer);
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
    public function isQuotePaymentMethodValid(
        QuoteTransfer $quoteTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer
    ): bool {
        return $this->getFactory()
            ->createPaymentMethodValidator()
            ->isQuotePaymentMethodValid($quoteTransfer, $checkoutResponseTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\Payment\Business\PaymentFacade::createPaymentProviderCollection()} instead.
     *
     * @param \Generated\Shared\Transfer\PaymentProviderTransfer $paymentProviderTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentProviderResponseTransfer
     */
    public function createPaymentProvider(PaymentProviderTransfer $paymentProviderTransfer): PaymentProviderResponseTransfer
    {
        return $this->getFactory()
            ->createPaymentWriter()
            ->createPaymentProvider($paymentProviderTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\Payment\Business\PaymentFacade::createPaymentMethodCollection()} instead.
     *
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodResponseTransfer
     */
    public function createPaymentMethod(PaymentMethodTransfer $paymentMethodTransfer): PaymentMethodResponseTransfer
    {
        return $this->getFactory()
            ->createPaymentWriter()
            ->createPaymentMethod($paymentMethodTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodResponseTransfer
     */
    public function deactivatePaymentMethod(PaymentMethodTransfer $paymentMethodTransfer): PaymentMethodResponseTransfer
    {
        return $this->getFactory()
            ->createPaymentWriter()
            ->deactivatePaymentMethod($paymentMethodTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodResponseTransfer
     */
    public function activatePaymentMethod(PaymentMethodTransfer $paymentMethodTransfer): PaymentMethodResponseTransfer
    {
        return $this->getFactory()
            ->createPaymentWriter()
            ->activatePaymentMethod($paymentMethodTransfer);
    }

    /**
     * {@inheritDoc}
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
    public function checkoutPreCheck(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer): bool
    {
        return $this->getFactory()
            ->createCheckoutPaymentPluginExecutor()
            ->executePreCheckPlugin($quoteTransfer, $checkoutResponseTransfer);
    }

    /**
     * {@inheritDoc}
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
    public function checkoutPostCheck(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer): void
    {
        $this->getFactory()
            ->createCheckoutPaymentPluginExecutor()
            ->executePostCheckPlugin($quoteTransfer, $checkoutResponseTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use QuoteTransfer.payments or OrderTransfer.payments instead to get amount per payment method.
     *
     * @param \Generated\Shared\Transfer\SalesPaymentTransfer $salesPaymentTransfer
     *
     * @return int
     */
    public function getPaymentMethodPriceToPay(SalesPaymentTransfer $salesPaymentTransfer): int
    {
        return $this->getFactory()
            ->createSalesPaymentReader()
            ->getPaymentMethodPriceToPay($salesPaymentTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\SalesPayment\Business\SalesPaymentFacade::expandOrderWithPayments()} instead.
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function hydrateOrderPayments(OrderTransfer $orderTransfer): OrderTransfer
    {
        return $this->getFactory()
            ->createPaymentHydrator()
            ->hydrateOrderWithPayment($orderTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    public function savePaymentForCheckout(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse): void
    {
        $this->getFactory()
            ->createCheckoutPaymentPluginExecutor()
            ->executeOrderSaverPlugin($quoteTransfer, $checkoutResponse);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\Payment\Business\PaymentFacade::getPaymentProviderCollection()} instead.
     *
     * @param \Generated\Shared\Transfer\PaymentProviderTransfer $paymentProviderTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentProviderTransfer|null
     */
    public function findPaymentProvider(PaymentProviderTransfer $paymentProviderTransfer): ?PaymentProviderTransfer
    {
        return $this->getFactory()
            ->createPaymentProviderReader()
            ->findPaymentProvider($paymentProviderTransfer);
    }

    /**
     * {@inheritDoc}
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
    public function sendEventPaymentCancelReservationPending(array $orderItemIds, OrderTransfer $orderTransfer): void
    {
        $this->getFactory()
            ->createMessageEmitter()
            ->sendEventPaymentCancelReservationPending($orderItemIds, $orderTransfer);
    }

    /**
     * {@inheritDoc}
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
    public function sendEventPaymentConfirmationPending(array $orderItemIds, int $orderItemsTotal, OrderTransfer $orderTransfer): void
    {
        $this->getFactory()
            ->createMessageEmitter()
            ->sendEventPaymentConfirmationPending($orderItemIds, $orderItemsTotal, $orderTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $orderPaymentEventTransfer
     *
     * @return void
     */
    public function triggerPaymentMessageOmsEvent(TransferInterface $orderPaymentEventTransfer): void
    {
        $this->getFactory()->createPaymentMessageOmsEventEmitter()->triggerPaymentMessageOmsEvent($orderPaymentEventTransfer);
    }

    /**
     * {@inheritDoc}
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
    ): void {
        $this->getFactory()
            ->createMessageEmitter()
            ->sendEventPaymentRefundPending($orderItemIds, $orderItemsTotal, $orderTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PaymentProviderCriteriaTransfer $paymentProviderCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentProviderCollectionTransfer
     */
    public function getPaymentProviderCollection(PaymentProviderCriteriaTransfer $paymentProviderCriteriaTransfer): PaymentProviderCollectionTransfer
    {
        return $this->getRepository()->getPaymentProviderCollection($paymentProviderCriteriaTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PaymentMethodCriteriaTransfer $paymentMethodCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodCollectionTransfer
     */
    public function getPaymentMethodCollection(PaymentMethodCriteriaTransfer $paymentMethodCriteriaTransfer): PaymentMethodCollectionTransfer
    {
        return $this->getRepository()->getPaymentMethodCollection($paymentMethodCriteriaTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PaymentProviderCollectionRequestTransfer $paymentProviderCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentProviderCollectionResponseTransfer
     */
    public function createPaymentProviderCollection(
        PaymentProviderCollectionRequestTransfer $paymentProviderCollectionRequestTransfer
    ): PaymentProviderCollectionResponseTransfer {
        return $this->getFactory()
            ->createPaymentProviderCreator()
            ->createPaymentProviderCollection($paymentProviderCollectionRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PaymentMethodCollectionRequestTransfer $paymentMethodCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodCollectionResponseTransfer
     */
    public function createPaymentMethodCollection(
        PaymentMethodCollectionRequestTransfer $paymentMethodCollectionRequestTransfer
    ): PaymentMethodCollectionResponseTransfer {
        return $this->getFactory()
            ->createPaymentMethodCreator()
            ->createPaymentMethodCollection($paymentMethodCollectionRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentTransfer
     */
    public function expandPaymentWithPaymentSelection(PaymentTransfer $paymentTransfer, StoreTransfer $storeTransfer): PaymentTransfer
    {
        return $this->getFactory()
            ->createPaymentExpander()
            ->expandPaymentWithPaymentSelection($paymentTransfer, $storeTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $paymentProvider
     * @param string $paymentMethod
     *
     * @return string
     */
    public function generatePaymentMethodKey(string $paymentProvider, string $paymentMethod): string
    {
        return $this->getFactory()
            ->createPaymentMethodKeyGenerator()
            ->generate($paymentProvider, $paymentMethod);
    }
}
