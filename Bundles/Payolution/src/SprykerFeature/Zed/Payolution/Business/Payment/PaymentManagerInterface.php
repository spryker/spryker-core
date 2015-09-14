<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business\Payment;

use Generated\Shared\Transfer\PayolutionResponseTransfer;

interface PaymentManagerInterface
{
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

}
