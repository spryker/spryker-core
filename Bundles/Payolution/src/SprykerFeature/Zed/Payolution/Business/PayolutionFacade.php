<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business;

use Generated\Shared\Payolution\CheckoutRequestInterface;
use Generated\Shared\Payolution\OrderInterface;
use Generated\Shared\Payolution\PayolutionResponseInterface;
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
     * @return PayolutionResponseInterface
     */
    public function preCheckPayment(CheckoutRequestInterface $checkoutRequestTransfer)
    {
        $payolutionResponseTransfer = $this
            ->getDependencyContainer()
            ->createPaymentCommunicator()
            ->preCheckPayment($checkoutRequestTransfer);

        return $payolutionResponseTransfer;
    }

    /**
     * @param int $idPayment
     *
     * @return PayolutionResponseInterface
     */
    public function preAuthorizePayment($idPayment)
    {
        return $this
            ->getDependencyContainer()
            ->createPaymentCommunicator()
            ->preAuthorizePayment($idPayment);
    }

    /**
     * @param int $idPayment
     *
     * @return PayolutionResponseInterface
     */
    public function reAuthorizePayment($idPayment)
    {
        return $this
            ->getDependencyContainer()
            ->createPaymentCommunicator()
            ->reAuthorizePayment($idPayment);
    }

    /**
     * @param int $idPayment
     *
     * @return PayolutionResponseInterface
     */
    public function revertPayment($idPayment)
    {
        return $this
            ->getDependencyContainer()
            ->createPaymentCommunicator()
            ->revertPayment($idPayment);
    }

    /**
     * @param int $idPayment
     *
     * @return PayolutionResponseInterface
     */
    public function capturePayment($idPayment)
    {
        return $this
            ->getDependencyContainer()
            ->createPaymentCommunicator()
            ->capturePayment($idPayment);
    }

    /**
     * @param int $idPayment
     *
     * @return PayolutionResponseInterface
     */
    public function refundPayment($idPayment)
    {
        return $this
            ->getDependencyContainer()
            ->createPaymentCommunicator()
            ->refundPayment($idPayment);
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
