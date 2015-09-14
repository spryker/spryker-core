<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business\Payment;

use Generated\Shared\Transfer\PayolutionRequestTransfer;
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
     * @return PayolutionRequestTransfer
     */
    public function mapToPreAuthorization(SpyPaymentPayolution $paymentEntity);

    /**
     * @param SpyPaymentPayolution $paymentEntity
     * @param string $uniqueId
     *
     * @return PayolutionRequestTransfer
     */
    public function mapToReAuthorization(SpyPaymentPayolution $paymentEntity, $uniqueId);

}
