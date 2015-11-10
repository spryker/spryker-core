<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business\Payment\Method\invoice;

use Generated\Shared\Payolution\CheckoutRequestInterface;
use Orm\Zed\Payolution\Persistence\SpyPaymentPayolution;

interface InvoiceInterface
{

    /**
     * @param CheckoutRequestInterface $checkoutRequestTransfer
     *
     * @return array
     */
    public function buildPreCheckRequest(CheckoutRequestInterface $checkoutRequestTransfer);

    /**
     * @param SpyPaymentPayolution $paymentEntity
     *
     * @return array
     */
    public function buildPreAuthorizationRequest(SpyPaymentPayolution $paymentEntity);

    /**
     * @param SpyPaymentPayolution $paymentEntity
     * @param string $uniqueId
     *
     * @return array
     */
    public function buildReAuthorizationRequest(SpyPaymentPayolution $paymentEntity, $uniqueId);

    /**
     * @param SpyPaymentPayolution $paymentEntity
     * @param int $uniqueId
     *
     * @return array
     */
    public function buildRevertRequest(SpyPaymentPayolution $paymentEntity, $uniqueId);

    /**
     * @param SpyPaymentPayolution $paymentEntity
     * @param string $uniqueId
     *
     * @return array
     */
    public function buildCaptureRequest(SpyPaymentPayolution $paymentEntity, $uniqueId);

    /**
     * @param SpyPaymentPayolution $paymentEntity
     * @param int $uniqueId
     *
     * @return array
     */
    public function buildRefundRequest(SpyPaymentPayolution $paymentEntity, $uniqueId);

    /**
     * @return string
     */
    public function getAccountBrand();

    /**
     * @return int
     */
    public function getMinGrandTotal();

    /**
     * @return int
     */
    public function getMaxGrandTotal();

}
