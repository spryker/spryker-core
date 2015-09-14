<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace SprykerFeature\Zed\Payolution\Business\Payment\MethodMapper;

use SprykerFeature\Zed\Payolution\Business\Api\Request\PreAuthorizationRequest;
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

    /**
     * @param SpyPaymentPayolution $payment
     *
     * @return PreAuthorizationRequest
     */
    public function mapToPreAuthorization(SpyPaymentPayolution $payment)
    {
        $request = new PreAuthorizationRequest();

        return $request;
    }

    /**
     * @param SpyPaymentPayolution $paymentEntity
     * @param string $uniqueId
     *
     * @return PayolutionRequestTransfer
     */
    public function mapToReAuthorization(SpyPaymentPayolution $paymentEntity, $uniqueId)
    {
    }

    /**
     * @return string
     */
    protected function getChannel()
    {
        return $this->getConfig()->getChannelInstallment();
    }

}
