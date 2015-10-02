<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace SprykerFeature\Zed\Payolution\Business\Payment\MethodMapper;

use Generated\Shared\Payolution\PayolutionRequestInterface;
use SprykerFeature\Zed\Payolution\Business\Api\Constants;
use SprykerFeature\Zed\Payolution\Persistence\Propel\SpyPaymentPayolution;

class Invoice extends AbstractMethodMapper
{

    /**
     * @param SpyPaymentPayolution $paymentEntity
     * @param string $uniqueId
     *
     * @return PayolutionRequestInterface
     */
    public function mapToCapture(SpyPaymentPayolution $paymentEntity, $uniqueId)
    {
        $requestTransfer = $this->getBaseRequestTransferForPayment($paymentEntity);
        $requestTransfer
            ->setPaymentCode(Constants::PAYMENT_CODE_CAPTURE)
            ->setIdentificationReferenceid($uniqueId);

        return $requestTransfer;
    }

    /**
     * @return string
     */
    public function getAccountBrand()
    {
        return Constants::ACCOUNT_BRAND_INVOICE;
    }

}
