<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business;

use Generated\Shared\Payolution\CheckoutRequestInterface;
use Generated\Shared\Payolution\OrderInterface;
use Generated\Shared\Payolution\PayolutionTransactionResponseInterface;
use Generated\Shared\Payolution\PayolutionCalculationResponseInterface;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;

/**
 * @method PayolutionDependencyContainer getDependencyContainer()
 */
class PayolutionFacade extends AbstractFacade
{

    /**
     * @param OrderInterface $orderTransfer
     */
    public function saveOrderPayment(OrderInterface $orderTransfer)
    {
        return $this
            ->getDependencyContainer()
            ->createOrderSaver()
            ->saveOrderPayment($orderTransfer);
    }

    /**
     * @param CheckoutRequestInterface $checkoutRequestTransfer
     *
     * @return PayolutionTransactionResponseInterface
     */
    public function preCheckPayment(CheckoutRequestInterface $checkoutRequestTransfer)
    {
        $payolutionResponseTransfer = $this
            ->getDependencyContainer()
            ->createPaymentTransactionHandler()
            ->preCheckPayment($checkoutRequestTransfer);

        return $payolutionResponseTransfer;
    }

    /**
     * @param int $idPayment
     *
     * @return PayolutionTransactionResponseInterface
     */
    public function preAuthorizePayment($idPayment)
    {
        return $this
            ->getDependencyContainer()
            ->createPaymentTransactionHandler()
            ->preAuthorizePayment($idPayment);
    }

    /**
     * @param int $idPayment
     *
     * @return PayolutionTransactionResponseInterface
     */
    public function reAuthorizePayment($idPayment)
    {
        return $this
            ->getDependencyContainer()
            ->createPaymentTransactionHandler()
            ->reAuthorizePayment($idPayment);
    }

    /**
     * @param int $idPayment
     *
     * @return PayolutionTransactionResponseInterface
     */
    public function revertPayment($idPayment)
    {
        return $this
            ->getDependencyContainer()
            ->createPaymentTransactionHandler()
            ->revertPayment($idPayment);
    }

    /**
     * @param int $idPayment
     *
     * @return PayolutionTransactionResponseInterface
     */
    public function capturePayment($idPayment)
    {
        return $this
            ->getDependencyContainer()
            ->createPaymentTransactionHandler()
            ->capturePayment($idPayment);
    }

    /**
     * @param int $idPayment
     *
     * @return PayolutionTransactionResponseInterface
     */
    public function refundPayment($idPayment)
    {
        return $this
            ->getDependencyContainer()
            ->createPaymentTransactionHandler()
            ->refundPayment($idPayment);
    }

    /**
     * @param CheckoutRequestInterface $checkoutRequestTransfer
     *
     * @return PayolutionCalculationResponseInterface
     */
    public function calculateInstallmentPayments(CheckoutRequestInterface $checkoutRequestTransfer)
    {
        $payolutionResponseTransfer = $this
            ->getDependencyContainer()
            ->createPaymentCalculationHandler()
            ->calculateInstallmentPayments($checkoutRequestTransfer);

        return $payolutionResponseTransfer;
    }

    /**
     * @param OrderInterface $orderTransfer
     *
     * @return bool
     */
    public function isPreAuthorizationApproved(OrderInterface $orderTransfer)
    {
        return $this
            ->getDependencyContainer()
            ->createTransactionStatusLog()
            ->isPreAuthorizationApproved($orderTransfer);
    }

    /**
     * @param OrderInterface $orderTransfer
     *
     * @return bool
     */
    public function isReAuthorizationApproved(OrderInterface $orderTransfer)
    {
        return $this
            ->getDependencyContainer()
            ->createTransactionStatusLog()
            ->isReAuthorizationApproved($orderTransfer);
    }

    /**
     * @param OrderInterface $orderTransfer
     *
     * @return bool
     */
    public function isReversalApproved(OrderInterface $orderTransfer)
    {
        return $this
            ->getDependencyContainer()
            ->createTransactionStatusLog()
            ->isReversalApproved($orderTransfer);
    }

    /**
     * @param OrderInterface $orderTransfer
     *
     * @return bool
     */
    public function isCaptureApproved(OrderInterface $orderTransfer)
    {
        return $this
            ->getDependencyContainer()
            ->createTransactionStatusLog()
            ->isCaptureApproved($orderTransfer);
    }

    /**
     * @param OrderInterface $orderTransfer
     *
     * @return bool
     */
    public function isRefundApproved(OrderInterface $orderTransfer)
    {
        return $this
            ->getDependencyContainer()
            ->createTransactionStatusLog()
            ->isRefundApproved($orderTransfer);
    }

}
