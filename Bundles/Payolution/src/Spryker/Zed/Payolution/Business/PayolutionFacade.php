<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payolution\Business;

use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Payolution\Business\PayolutionBusinessFactory getFactory()
 */
class PayolutionFacade extends AbstractFacade implements PayolutionFacadeInterface
{

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function saveOrderPayment(OrderTransfer $orderTransfer)
    {
        $this->getFactory()
            ->createOrderSaver()
            ->saveOrderPayment($orderTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\CheckoutRequestTransfer $checkoutRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PayolutionTransactionResponseTransfer
     */
    public function preCheckPayment(CheckoutRequestTransfer $checkoutRequestTransfer)
    {
        $payolutionResponseTransfer = $this
            ->getFactory()
            ->createPaymentTransactionHandler()
            ->preCheckPayment($checkoutRequestTransfer);

        return $payolutionResponseTransfer;
    }

    /**
     * @api
     *
     * @param int $idPayment
     *
     * @return \Generated\Shared\Transfer\PayolutionTransactionResponseTransfer
     */
    public function preAuthorizePayment($idPayment)
    {
        return $this
            ->getFactory()
            ->createPaymentTransactionHandler()
            ->preAuthorizePayment($idPayment);
    }

    /**
     * @api
     *
     * @param int $idPayment
     *
     * @return \Generated\Shared\Transfer\PayolutionTransactionResponseTransfer
     */
    public function reAuthorizePayment($idPayment)
    {
        return $this
            ->getFactory()
            ->createPaymentTransactionHandler()
            ->reAuthorizePayment($idPayment);
    }

    /**
     * @api
     *
     * @param int $idPayment
     *
     * @return \Generated\Shared\Transfer\PayolutionTransactionResponseTransfer
     */
    public function revertPayment($idPayment)
    {
        return $this
            ->getFactory()
            ->createPaymentTransactionHandler()
            ->revertPayment($idPayment);
    }

    /**
     * @api
     *
     * @param int $idPayment
     *
     * @return \Generated\Shared\Transfer\PayolutionTransactionResponseTransfer
     */
    public function capturePayment($idPayment)
    {
        return $this
            ->getFactory()
            ->createPaymentTransactionHandler()
            ->capturePayment($idPayment);
    }

    /**
     * @api
     *
     * @param int $idPayment
     *
     * @return \Generated\Shared\Transfer\PayolutionTransactionResponseTransfer
     */
    public function refundPayment($idPayment)
    {
        return $this
            ->getFactory()
            ->createPaymentTransactionHandler()
            ->refundPayment($idPayment);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\CheckoutRequestTransfer $checkoutRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PayolutionCalculationResponseTransfer
     */
    public function calculateInstallmentPayments(CheckoutRequestTransfer $checkoutRequestTransfer)
    {
        $payolutionResponseTransfer = $this
            ->getFactory()
            ->createPaymentCalculationHandler()
            ->calculateInstallmentPayments($checkoutRequestTransfer);

        return $payolutionResponseTransfer;
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isPreAuthorizationApproved(OrderTransfer $orderTransfer)
    {
        return $this
            ->getFactory()
            ->createTransactionStatusLog()
            ->isPreAuthorizationApproved($orderTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isReAuthorizationApproved(OrderTransfer $orderTransfer)
    {
        return $this
            ->getFactory()
            ->createTransactionStatusLog()
            ->isReAuthorizationApproved($orderTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isReversalApproved(OrderTransfer $orderTransfer)
    {
        return $this
            ->getFactory()
            ->createTransactionStatusLog()
            ->isReversalApproved($orderTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isCaptureApproved(OrderTransfer $orderTransfer)
    {
        return $this
            ->getFactory()
            ->createTransactionStatusLog()
            ->isCaptureApproved($orderTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isRefundApproved(OrderTransfer $orderTransfer)
    {
        return $this
            ->getFactory()
            ->createTransactionStatusLog()
            ->isRefundApproved($orderTransfer);
    }

}
