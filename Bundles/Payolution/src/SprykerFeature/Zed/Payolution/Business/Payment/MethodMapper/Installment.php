<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace SprykerFeature\Zed\Payolution\Business\Payment\MethodMapper;

use SprykerFeature\Zed\Payolution\Business\Api\Constants;
use SprykerFeature\Zed\Payolution\Business\Exception\PaymentMethodMapperMethodNotAvailableException;
use SprykerFeature\Zed\Payolution\Persistence\Propel\SpyPaymentPayolution;

class Installment extends AbstractMethodMapper
{

    /**
     * @return string
     */
    public function getAccountBrand()
    {
        return Constants::ACCOUNT_BRAND_INSTALLMENT;
    }

    /**
     * @param SpyPaymentPayolution $paymentEntity
     * @param string $uniqueId
     *
     * @throws PaymentMethodMapperMethodNotAvailableException
     */
    public function mapToCapture(SpyPaymentPayolution $paymentEntity, $uniqueId)
    {
        throw new PaymentMethodMapperMethodNotAvailableException(
            'Capture method is not allowed for installment payments'
        );
    }

}
