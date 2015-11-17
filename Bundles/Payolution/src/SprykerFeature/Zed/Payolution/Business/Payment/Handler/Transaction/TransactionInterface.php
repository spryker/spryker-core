<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business\Payment\Handler\Transaction;

use Generated\Shared\Payolution\PayolutionTransactionResponseInterface;
use Generated\Shared\Payolution\CheckoutRequestInterface;

interface TransactionInterface
{

    /**
     * @param CheckoutRequestInterface $checkoutRequestTransfer
     *
     * @return PayolutionTransactionResponseInterface
     */
    public function preCheckPayment(CheckoutRequestInterface $checkoutRequestTransfer);

    /**
     * @param int $idPayment
     *
     * @return PayolutionTransactionResponseInterface
     */
    public function preAuthorizePayment($idPayment);

    /**
     * @param int $idPayment
     *
     * @return PayolutionTransactionResponseInterface
     */
    public function reAuthorizePayment($idPayment);

    /**
     * @param int $idPayment
     *
     * @return PayolutionTransactionResponseInterface
     */
    public function revertPayment($idPayment);

    /**
     * @param int $idPayment
     *
     * @return PayolutionTransactionResponseInterface
     */
    public function capturePayment($idPayment);

    /**
     * @param int $idPayment
     *
     * @return PayolutionTransactionResponseInterface
     */
    public function refundPayment($idPayment);

}
