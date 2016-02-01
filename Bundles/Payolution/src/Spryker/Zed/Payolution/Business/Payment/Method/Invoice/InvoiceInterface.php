<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Payolution\Business\Payment\Method\Invoice;

use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Orm\Zed\Payolution\Persistence\SpyPaymentPayolution;

interface InvoiceInterface
{

    /**
     * @param \Generated\Shared\Transfer\CheckoutRequestTransfer $checkoutRequestTransfer
     *
     * @return array
     */
    public function buildPreCheckRequest(CheckoutRequestTransfer $checkoutRequestTransfer);

    /**
     * @param \Orm\Zed\Payolution\Persistence\SpyPaymentPayolution $paymentEntity
     *
     * @return array
     */
    public function buildPreAuthorizationRequest(SpyPaymentPayolution $paymentEntity);

    /**
     * @param \Orm\Zed\Payolution\Persistence\SpyPaymentPayolution $paymentEntity
     * @param string $uniqueId
     *
     * @return array
     */
    public function buildReAuthorizationRequest(SpyPaymentPayolution $paymentEntity, $uniqueId);

    /**
     * @param \Orm\Zed\Payolution\Persistence\SpyPaymentPayolution $paymentEntity
     * @param int $uniqueId
     *
     * @return array
     */
    public function buildRevertRequest(SpyPaymentPayolution $paymentEntity, $uniqueId);

    /**
     * @param \Orm\Zed\Payolution\Persistence\SpyPaymentPayolution $paymentEntity
     * @param string $uniqueId
     *
     * @return array
     */
    public function buildCaptureRequest(SpyPaymentPayolution $paymentEntity, $uniqueId);

    /**
     * @param \Orm\Zed\Payolution\Persistence\SpyPaymentPayolution $paymentEntity
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
