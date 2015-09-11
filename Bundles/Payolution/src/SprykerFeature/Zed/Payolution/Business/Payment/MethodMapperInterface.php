<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business\Payment;

use SprykerFeature\Zed\Payolution\Business\Api\Request\PreAuthorizationRequest;
use SprykerFeature\Zed\Payolution\Persistence\Propel\SpyPaymentPayolution;

interface MethodMapperInterface
{

    const INVOICE = 'INVOICE_METHOD_MAPPER';
    const INSTALLMENT = 'INSTALLMENT_METHOD_MAPPER';

    /**
     * @return string
     */
    public function getName();

    /**
     * @param SpyPaymentPayolution $paymentEntity
     *
     * @return mixed
     */
    public function mapToPreAuthorization(SpyPaymentPayolution $paymentEntity);

}
