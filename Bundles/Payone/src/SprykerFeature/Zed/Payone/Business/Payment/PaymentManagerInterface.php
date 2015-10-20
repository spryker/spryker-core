<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payone\Business\Payment;

use Generated\Shared\Checkout\CheckoutResponseInterface;
use Generated\Shared\Payone\OrderInterface;
use Generated\Shared\Payone\PayoneCreditCardInterface;
use Generated\Shared\Payone\PayoneRefundInterface;
use Generated\Shared\Refund\PaymentDataInterface;
use Generated\Shared\Transfer\PaymentDataTransfer;
use Generated\Shared\Transfer\PayoneCreditCardCheckRequestDataTransfer;
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
     * @return array
     */
    public function getPaymentLogs(ObjectCollection $orders);

    /**
     * @param PayoneCreditCardCheckRequestDataTransfer $creditCardCheckRequestDataTransfer
     *
     * @return array
     */
    public function getCreditCardCheckRequestData(PayoneCreditCardCheckRequestDataTransfer $creditCardCheckRequestDataTransfer);

    /**
     * @param OrderInterface $orderTransfer
     *
     * @return bool
     */
    public function isRefundPossible(OrderInterface $orderTransfer);

    /**
     * @param OrderInterface $orderTransfer
     *
     * @return bool
     */
    public function isPaymentDataRequired(OrderInterface $orderTransfer);

    /**
     * @param OrderInterface $orderTransfer
     * @param CheckoutResponseInterface $checkoutResponse
     *
     * @return CheckoutResponseInterface
     */
    public function postSaveHook(OrderInterface $orderTransfer, CheckoutResponseInterface $checkoutResponse);

    /**
     * @param int $idPayment
     *
     * @return PaymentDataInterface
     */
    public function getPaymentData($idPayment);

    /**
     * @param PaymentDataTransfer $paymentDataTransfer
     * @param int $idOrder
     *
     * @return void
     */
    public function updatePaymentDetail(PaymentDataTransfer $paymentDataTransfer, $idOrder);

}
