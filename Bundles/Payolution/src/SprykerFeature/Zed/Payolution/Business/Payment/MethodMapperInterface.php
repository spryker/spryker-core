<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business\Payment;

use SprykerFeature\Zed\Payolution\Business\Api\Request\PreAuthorizationRequest;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder;

interface MethodMapperInterface
{

    const INVOICE = 'INVOICE_METHOD_MAPPER';
    const INSTALLMENT = 'INSTALLMENT_METHOD_MAPPER';

    /**
     * @return string
     */
    public function getName();

    /**
     * @param SpySalesOrder $salesOrder
     * @param string $clientIp
     *
     * @return PreAuthorizationRequest
     */
    public function mapToPreAuthorization(SpySalesOrder $salesOrder, $clientIp);

}
