<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payone\Business\Payment;

use Generated\Shared\Payone\PayoneCreditCardInterface;
use Generated\Shared\Payone\PayoneRefundInterface;
use Generated\Shared\Transfer\PayonePaymentLogTransfer;
use Propel\Runtime\Collection\ObjectCollection;
use SprykerFeature\Zed\Payone\Business\Api\Response\Container\AuthorizationResponseContainer;
use SprykerFeature\Zed\Payone\Business\Api\Response\Container\CaptureResponseContainer;
use SprykerFeature\Zed\Payone\Business\Api\Response\Container\CreditCardCheckResponseContainer;
use SprykerFeature\Zed\Payone\Business\Api\Response\Container\DebitResponseContainer;
use SprykerFeature\Zed\Payone\Business\Api\Response\Container\RefundResponseContainer;

interface PaymentManagerInterface
{

    /**
     * @param PaymentMethodMapperInterface $paymentMethodMapper
     */
    public function registerPaymentMethodMapper(PaymentMethodMapperInterface $paymentMethodMapper);

    /**
     * @param PayoneRefundInterface $refundTransfer
     *
     * @return RefundResponseContainer
     */
    public function refundPayment(PayoneRefundInterface $refundTransfer);

    /**
     * @param int $idPayment
     *
     * @return DebitResponseContainer
     */
    public function debitPayment($idPayment);

    /**
     * @param int $idPayment
     *
     * @return AuthorizationResponseContainer
     */
    public function authorizePayment($idPayment);

    /**
     * @param $idPayment
     *
     * @return AuthorizationResponseContainer
     */
    public function preAuthorizePayment($idPayment);

    /**
     * @param $idPayment
     *
     * @return CaptureResponseContainer
     */
    public function capturePayment($idPayment);

    /**
     * @param PayoneCreditCardInterface $creditCardData
     *
     * @return CreditCardCheckResponseContainer
     */
    public function creditCardCheck(PayoneCreditCardInterface $creditCardData);

    /**
     * @param ObjectCollection $orders
     *
     * @return PayonePaymentLogTransfer[]
     */
    public function getPaymentLogs(ObjectCollection $orders);

}
