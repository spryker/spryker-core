<?php
// phpcs:ignoreFile
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Communication\Plugin\MessageBroker;

use Generated\Shared\Transfer\PaymentAuthorizationFailedTransfer;
use Generated\Shared\Transfer\PaymentAuthorizedTransfer;
use Generated\Shared\Transfer\PaymentCanceledTransfer;
use Generated\Shared\Transfer\PaymentCancellationFailedTransfer;
use Generated\Shared\Transfer\PaymentCancelReservationFailedTransfer;
use Generated\Shared\Transfer\PaymentCapturedTransfer;
use Generated\Shared\Transfer\PaymentCaptureFailedTransfer;
use Generated\Shared\Transfer\PaymentConfirmationFailedTransfer;
use Generated\Shared\Transfer\PaymentConfirmedTransfer;
use Generated\Shared\Transfer\PaymentPreauthorizationFailedTransfer;
use Generated\Shared\Transfer\PaymentPreauthorizedTransfer;
use Generated\Shared\Transfer\PaymentRefundedTransfer;
use Generated\Shared\Transfer\PaymentRefundFailedTransfer;
use Generated\Shared\Transfer\PaymentReservationCanceledTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageHandlerPluginInterface;

/**
 * @method \Spryker\Zed\Payment\Business\PaymentFacadeInterface getFacade()
 * @method \Spryker\Zed\Payment\Persistence\PaymentQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Payment\PaymentConfig getConfig()
 * @method \Spryker\Zed\Payment\Communication\PaymentCommunicationFactory getFactory()
 */
class PaymentOperationsMessageHandlerPlugin extends AbstractPlugin implements MessageHandlerPluginInterface
{
    /**
     * {@inheritDoc}
     * - Return an array where the key is the class name to be handled and the value is the callable that handles the message.
     *
     * @api
     *
     * @return iterable
     */
    public function handles(): iterable
    {
        return [
            PaymentAuthorizedTransfer::class => [$this->getFacade(), 'triggerPaymentMessageOmsEvent'],
            PaymentAuthorizationFailedTransfer::class => [$this->getFacade(), 'triggerPaymentMessageOmsEvent'],
            PaymentCanceledTransfer::class => [$this->getFacade(), 'triggerPaymentMessageOmsEvent'],
            PaymentCancellationFailedTransfer::class => [$this->getFacade(), 'triggerPaymentMessageOmsEvent'],
            PaymentCapturedTransfer::class => [$this->getFacade(), 'triggerPaymentMessageOmsEvent'],
            PaymentCaptureFailedTransfer::class => [$this->getFacade(), 'triggerPaymentMessageOmsEvent'],
            PaymentRefundedTransfer::class => [$this->getFacade(), 'triggerPaymentMessageOmsEvent'],
            PaymentRefundFailedTransfer::class => [$this->getFacade(), 'triggerPaymentMessageOmsEvent'],

            // @deprecated
            PaymentPreauthorizedTransfer::class => [$this, 'onPaymentAuthorized'],
            PaymentPreauthorizationFailedTransfer::class => [$this, 'onPaymentAuthorizationFailed'],
            PaymentReservationCanceledTransfer::class => [$this, 'onPaymentCanceled'],
            PaymentCancelReservationFailedTransfer::class => [$this, 'onPaymentCancellationFailed'],
            PaymentConfirmedTransfer::class => [$this, 'onPaymentCaptured'],
            PaymentConfirmationFailedTransfer::class => [$this, 'onPaymentCaptureFailed'],
        ];
    }

    /**
     * @deprecated Don't use this method directly, this method is only used for BC reasons.
     *
     * @param \Generated\Shared\Transfer\PaymentPreauthorizedTransfer $paymentPreauthorizedTransfer
     *
     * @return void
     */
    public function onPaymentAuthorized(PaymentPreauthorizedTransfer $paymentPreauthorizedTransfer): void
    {
        $paymentAuthorizedTransfer = (new PaymentAuthorizedTransfer())->fromArray($paymentPreauthorizedTransfer->toArray(), true);

        $this->getFacade()->triggerPaymentMessageOmsEvent($paymentAuthorizedTransfer);
    }

    /**
     * @deprecated Don't use this method directly, this method is only used for BC reasons.
     *
     * @param \Generated\Shared\Transfer\PaymentPreauthorizationFailedTransfer $paymentPreauthorizationFailedTransfer
     *
     * @return void
     */
    public function onPaymentAuthorizationFailed(PaymentPreauthorizationFailedTransfer $paymentPreauthorizationFailedTransfer): void
    {
        $paymentAuthorizationFailedTransfer = (new PaymentAuthorizationFailedTransfer())->fromArray($paymentPreauthorizationFailedTransfer->toArray(), true);

        $this->getFacade()->triggerPaymentMessageOmsEvent($paymentAuthorizationFailedTransfer);
    }

    /**
     * @deprecated Don't use this method directly, this method is only used for BC reasons.
     *
     * @param \Generated\Shared\Transfer\PaymentReservationCanceledTransfer $paymentReservationCanceledTransfer
     *
     * @return void
     */
    public function onPaymentCanceled(PaymentReservationCanceledTransfer $paymentReservationCanceledTransfer): void
    {
        $paymentCanceledTransfer = (new PaymentCanceledTransfer())->fromArray($paymentReservationCanceledTransfer->toArray(), true);

        $this->getFacade()->triggerPaymentMessageOmsEvent($paymentCanceledTransfer);
    }

    /**
     * @deprecated Don't use this method directly, this method is only used for BC reasons.
     *
     * @param \Generated\Shared\Transfer\PaymentCancelReservationFailedTransfer $paymentCancelReservationFailedTransfer
     *
     * @return void
     */
    public function onPaymentCancelReservationFailed(PaymentCancelReservationFailedTransfer $paymentCancelReservationFailedTransfer): void
    {
        $paymentCancellationFailedTransfer = (new PaymentCancellationFailedTransfer())->fromArray($paymentCancelReservationFailedTransfer->toArray(), true);

        $this->getFacade()->triggerPaymentMessageOmsEvent($paymentCancellationFailedTransfer);
    }

    /**
     * @deprecated Don't use this method directly, this method is only used for BC reasons.
     *
     * @param \Generated\Shared\Transfer\PaymentConfirmedTransfer $paymentConfirmedTransfer
     *
     * @return void
     */
    public function onPaymentConfirmed(PaymentConfirmedTransfer $paymentConfirmedTransfer): void
    {
        $paymentCapturedTransfer = (new PaymentCapturedTransfer())->fromArray($paymentConfirmedTransfer->toArray(), true);

        $this->getFacade()->triggerPaymentMessageOmsEvent($paymentCapturedTransfer);
    }

    /**
     * @deprecated Don't use this method directly, this method is only used for BC reasons.
     *
     * @param \Generated\Shared\Transfer\PaymentConfirmationFailedTransfer $paymentConfirmationFailedTransfer
     *
     * @return void
     */
    public function onPaymentConfirmationFailed(PaymentConfirmationFailedTransfer $paymentConfirmationFailedTransfer): void
    {
        $capturePaymentFailedTransfer = (new PaymentCaptureFailedTransfer())->fromArray($paymentConfirmationFailedTransfer->toArray(), true);

        $this->getFacade()->triggerPaymentMessageOmsEvent($capturePaymentFailedTransfer);
    }
}
