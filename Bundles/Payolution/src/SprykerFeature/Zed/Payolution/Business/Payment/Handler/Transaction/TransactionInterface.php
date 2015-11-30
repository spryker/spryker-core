<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business\Payment\Handler\Transaction;

use Generated\Shared\Transfer\PayolutionTransactionResponseTransfer;
use Generated\Shared\Transfer\CheckoutRequestTransfer;

interface TransactionInterface
{

    /**
     * @param CheckoutRequestTransfer $checkoutRequestTransfer
     *
     * @return PayolutionTransactionResponseTransfer
     */
    public function preCheckPayment(CheckoutRequestTransfer $checkoutRequestTransfer);

    /**
     * @param int $idPayment
     *
     * @return PayolutionTransactionResponseTransfer
     */
    public function preAuthorizePayment($idPayment);

    /**
     * @param int $idPayment
     *
     * @return PayolutionTransactionResponseTransfer
     */
    public function reAuthorizePayment($idPayment);

    /**
     * @param int $idPayment
     *
     * @return PayolutionTransactionResponseTransfer
     */
    public function revertPayment($idPayment);

    /**
     * @param int $idPayment
     *
     * @return PayolutionTransactionResponseTransfer
     */
    public function capturePayment($idPayment);

    /**
     * @param int $idPayment
     *
     * @return PayolutionTransactionResponseTransfer
     */
    public function refundPayment($idPayment);

}
