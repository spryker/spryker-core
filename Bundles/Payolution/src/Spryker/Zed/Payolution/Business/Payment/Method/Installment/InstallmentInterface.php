<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Payolution\Business\Payment\Method\Installment;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Payolution\Persistence\SpyPaymentPayolution;

interface InstallmentInterface
{

    /**
     * @param QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    public function buildCalculationRequest(QuoteTransfer $quoteTransfer);

    /**
     * @param QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    public function buildPreCheckRequest(QuoteTransfer $quoteTransfer);

    /**
     * @param OrderTransfer $orderTransfer
     * @param SpyPaymentPayolution $paymentEntity
     *
     * @return array
     */
    public function buildPreAuthorizationRequest(OrderTransfer $orderTransfer, SpyPaymentPayolution $paymentEntity);

    /**
     * @param OrderTransfer $orderTransfer
     * @param SpyPaymentPayolution $paymentEntity
     * @param string $uniqueId
     *
     * @return array
     */
    public function buildReAuthorizationRequest(OrderTransfer $orderTransfer, SpyPaymentPayolution $paymentEntity, $uniqueId);

    /**
     * @param OrderTransfer $orderTransfer
     * @param SpyPaymentPayolution $paymentEntity
     * @param string $uniqueId
     *
     * @return array
     */
    public function buildRevertRequest(OrderTransfer $orderTransfer, SpyPaymentPayolution $paymentEntity, $uniqueId);

    /**
     * @param OrderTransfer $orderTransfer
     * @param SpyPaymentPayolution $paymentEntity
     * @param string $uniqueId
     *
     * @return array
     */
    public function buildCaptureRequest(OrderTransfer $orderTransfer, SpyPaymentPayolution $paymentEntity, $uniqueId);

    /**
     * @param OrderTransfer $orderTransfer
     * @param SpyPaymentPayolution $paymentEntity
     * @param string $uniqueId
     *
     * @return array
     */
    public function buildRefundRequest(OrderTransfer $orderTransfer, SpyPaymentPayolution $paymentEntity, $uniqueId);

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
