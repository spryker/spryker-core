<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business\Payment;

use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\PayolutionResponseTransfer;

interface PaymentManagerInterface
{

    /**
     * @param CheckoutRequestTransfer $checkoutRequestTransfer
     *
     * @return PayolutionResponseTransfer
     */
    public function preCheckPayment(CheckoutRequestTransfer $checkoutRequestTransfer);

    /**
     * @param int $idPayment
     *
     * @return PayolutionResponseTransfer
     */
    public function preAuthorizePayment($idPayment);

    /**
     * @param int $idPayment
     *
     * @return PayolutionResponseTransfer
     */
    public function reAuthorizePayment($idPayment);

    /**
     * @param int $idPayment
     *
     * @return PayolutionResponseTransfer
     */
    public function revertPayment($idPayment);

    /**
     * @param int $idPayment
     *
     * @return PayolutionResponseTransfer
     */
    public function capturePayment($idPayment);

    /**
     * @param int $idPayment
     *
     * @return PayolutionResponseTransfer
     */
    public function refundPayment($idPayment);

}
