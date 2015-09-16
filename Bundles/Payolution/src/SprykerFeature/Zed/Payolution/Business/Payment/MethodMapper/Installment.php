<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace SprykerFeature\Zed\Payolution\Business\Payment\MethodMapper;

use SprykerFeature\Zed\Payolution\Business\Payment\MethodMapperInterface;
use SprykerFeature\Zed\Payolution\Persistence\Propel\SpyPaymentPayolution;

class Installment extends AbstractMethodMapper
{

    /**
     * @return string
     */
    public function getName()
    {
        return MethodMapperInterface::INSTALLMENT;
    }

    public function mapToPreAuthorization(SpyPaymentPayolution $payment)
    {

    }

    public function mapToReAuthorization(SpyPaymentPayolution $paymentEntity, $uniqueId)
    {

    }

    public function mapToCapture(SpyPaymentPayolution $paymentEntity, $uniqueId)
    {
        // TODO: Implement mapToCapture() method.
    }


}
