<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business\Payment\MethodMapper;

use Generated\Shared\Payolution\CheckoutRequestInterface;
use Generated\Shared\Payolution\PayolutionRequestInterface;
use SprykerFeature\Zed\Payolution\Persistence\Propel\SpyPaymentPayolution;

interface MethodMapperInterface
{

    /**
     * @param  CheckoutRequestInterface $checkoutRequestTransfer
     *
     * @return PayolutionRequestInterface
     */
    public function mapToPreCheck(CheckoutRequestInterface $checkoutRequestTransfer);

    /**
     * @param SpyPaymentPayolution $paymentEntity
     *
     * @return PayolutionRequestInterface
     */
    public function mapToPreAuthorization(SpyPaymentPayolution $paymentEntity);

    /**
     * @param SpyPaymentPayolution $paymentEntity
     * @param string $uniqueId
     *
     * @return PayolutionRequestInterface
     */
    public function mapToReAuthorization(SpyPaymentPayolution $paymentEntity, $uniqueId);

    /**
     * @param SpyPaymentPayolution $paymentEntity
     * @param int $uniqueId
     *
     * @return PayolutionRequestInterface
     */
    public function mapToReversal(SpyPaymentPayolution $paymentEntity, $uniqueId);

    /**
     * @param SpyPaymentPayolution $paymentEntity
     * @param string $uniqueId
     *
     * @return PayolutionRequestInterface
     */
    public function mapToCapture(SpyPaymentPayolution $paymentEntity, $uniqueId);

    /**
     * @param SpyPaymentPayolution $paymentEntity
     * @param int $uniqueId
     *
     * @return PayolutionRequestInterface
     */
    public function mapToRefund(SpyPaymentPayolution $paymentEntity, $uniqueId);

    /**
     * @return string
     */
    public function getAccountBrand();

}
