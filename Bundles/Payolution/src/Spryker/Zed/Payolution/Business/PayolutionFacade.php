<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Payolution\Business;

use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PayolutionTransactionResponseTransfer;
use Generated\Shared\Transfer\PayolutionCalculationResponseTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method PayolutionDependencyContainer getBusinessFactory()
 */
class PayolutionFacade extends AbstractFacade
{

    /**
     * @param OrderTransfer $orderTransfer
     */
    public function saveOrderPayment(OrderTransfer $orderTransfer)
    {
        return $this
            ->getBusinessFactory()
            ->createOrderSaver()
            ->saveOrderPayment($orderTransfer);
    }

    /**
     * @param CheckoutRequestTransfer $checkoutRequestTransfer
     *
     * @return PayolutionTransactionResponseTransfer
     */
    public function preCheckPayment(CheckoutRequestTransfer $checkoutRequestTransfer)
    {
        $payolutionResponseTransfer = $this
            ->getBusinessFactory()
            ->createPaymentTransactionHandler()
            ->preCheckPayment($checkoutRequestTransfer);

        return $payolutionResponseTransfer;
    }

    /**
     * @param int $idPayment
     *
     * @return PayolutionTransactionResponseTransfer
     */
    public function preAuthorizePayment($idPayment)
    {
        return $this
            ->getBusinessFactory()
            ->createPaymentTransactionHandler()
            ->preAuthorizePayment($idPayment);
    }

    /**
     * @param int $idPayment
     *
     * @return PayolutionTransactionResponseTransfer
     */
    public function reAuthorizePayment($idPayment)
    {
        return $this
            ->getBusinessFactory()
            ->createPaymentTransactionHandler()
            ->reAuthorizePayment($idPayment);
    }

    /**
     * @param int $idPayment
     *
     * @return PayolutionTransactionResponseTransfer
     */
    public function revertPayment($idPayment)
    {
        return $this
            ->getBusinessFactory()
            ->createPaymentTransactionHandler()
            ->revertPayment($idPayment);
    }

    /**
     * @param int $idPayment
     *
     * @return PayolutionTransactionResponseTransfer
     */
    public function capturePayment($idPayment)
    {
        return $this
            ->getBusinessFactory()
            ->createPaymentTransactionHandler()
            ->capturePayment($idPayment);
    }

    /**
     * @param int $idPayment
     *
     * @return PayolutionTransactionResponseTransfer
     */
    public function refundPayment($idPayment)
    {
        return $this
            ->getBusinessFactory()
            ->createPaymentTransactionHandler()
            ->refundPayment($idPayment);
    }

    /**
     * @param CheckoutRequestTransfer $checkoutRequestTransfer
     *
     * @return PayolutionCalculationResponseTransfer
     */
    public function calculateInstallmentPayments(CheckoutRequestTransfer $checkoutRequestTransfer)
    {
        $payolutionResponseTransfer = $this
            ->getBusinessFactory()
            ->createPaymentCalculationHandler()
            ->calculateInstallmentPayments($checkoutRequestTransfer);

        return $payolutionResponseTransfer;
    }

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isPreAuthorizationApproved(OrderTransfer $orderTransfer)
    {
        return $this
            ->getBusinessFactory()
            ->createTransactionStatusLog()
            ->isPreAuthorizationApproved($orderTransfer);
    }

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isReAuthorizationApproved(OrderTransfer $orderTransfer)
    {
        return $this
            ->getBusinessFactory()
            ->createTransactionStatusLog()
            ->isReAuthorizationApproved($orderTransfer);
    }

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isReversalApproved(OrderTransfer $orderTransfer)
    {
        return $this
            ->getBusinessFactory()
            ->createTransactionStatusLog()
            ->isReversalApproved($orderTransfer);
    }

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isCaptureApproved(OrderTransfer $orderTransfer)
    {
        return $this
            ->getBusinessFactory()
            ->createTransactionStatusLog()
            ->isCaptureApproved($orderTransfer);
    }

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isRefundApproved(OrderTransfer $orderTransfer)
    {
        return $this
            ->getBusinessFactory()
            ->createTransactionStatusLog()
            ->isRefundApproved($orderTransfer);
    }

}
