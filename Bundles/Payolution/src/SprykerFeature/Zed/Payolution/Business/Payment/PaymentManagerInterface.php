<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business\Payment;

use SprykerFeature\Zed\Payolution\Business\Api\Response\PreAuthorizationResponse;

interface PaymentManagerInterface
{
    /**
     * @param int $idOrder
     * @param string $clientIp
     *
     * @return PreAuthorizationResponse
     */
    public function preAuthorizePaymentFromOrder($idOrder, $clientIp);

}
