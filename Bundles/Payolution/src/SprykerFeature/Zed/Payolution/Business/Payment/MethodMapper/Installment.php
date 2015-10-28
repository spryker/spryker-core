<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace SprykerFeature\Zed\Payolution\Business\Payment\MethodMapper;

use SprykerFeature\Shared\Payolution\PayolutionApiConstants;
use SprykerFeature\Zed\Payolution\Business\Exception\PaymentMethodMapperMethodNotAvailableException;
use Orm\Zed\Payolution\Persistence\SpyPaymentPayolution;

class Installment extends AbstractMethodMapper
{

    /**
     * @return string
     */
    public function getAccountBrand()
    {
        return PayolutionApiConstants::BRAND_INSTALLMENT;
    }

    /**
     * @param SpyPaymentPayolution $paymentEntity
     * @param string $uniqueId
     *
     * @throws PaymentMethodMapperMethodNotAvailableException
     *
     * @return PayolutionRequestInterface
     */
    public function mapToCapture(SpyPaymentPayolution $paymentEntity, $uniqueId)
    {
        throw new PaymentMethodMapperMethodNotAvailableException(
            'Capture method is not allowed for installment payments'
        );
    }

}
