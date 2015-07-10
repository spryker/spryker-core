<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payone\Business\Payment;

use Generated\Shared\Payone\AuthorizationInterface;
use Generated\Shared\Payone\CaptureInterface;
use Generated\Shared\Payone\CreditCardInterface;
use Generated\Shared\Payone\DebitInterface;
use Generated\Shared\Payone\OrderInterface as PayoneOrderInterface;
use Generated\Shared\Payone\RefundInterface;
use Generated\Shared\Transfer\PayonePaymentTransfer;
use SprykerFeature\Zed\Payone\Business\Api\Response\Container\AuthorizationResponseContainer;
use SprykerFeature\Zed\Payone\Business\Api\Response\Container\CaptureResponseContainer;
use SprykerFeature\Zed\Payone\Business\Api\Response\Container\CreditCardCheckResponseContainer;
use SprykerFeature\Zed\Payone\Business\Api\Response\Container\DebitResponseContainer;
use SprykerFeature\Zed\Payone\Business\Api\Response\Container\RefundResponseContainer;

interface PaymentManagerInterface
{

    /**
     * @param DebitInterface $debitData
     *
     * @return DebitResponseContainer
     */
    public function debit(DebitInterface $debitData);

    /**
     * @param CaptureInterface $captureData
     *
     * @return CaptureResponseContainer
     */
    public function capture(CaptureInterface $captureData);

    /**
     * @param PaymentMethodMapperInterface $paymentMethodMapper
     */
    public function registerPaymentMethodMapper(PaymentMethodMapperInterface $paymentMethodMapper);

    /**
     * @param RefundInterface $refundData
     *
     * @return RefundResponseContainer
     */
    public function refund(RefundInterface $refundData);

    /**
     * @param AuthorizationInterface $authorizationData
     *
     * @return AuthorizationResponseContainer
     */
    public function preAuthorize(AuthorizationInterface $authorizationData);

    /**
     * @param AuthorizationInterface $authorizationData
     *
     * @return AuthorizationResponseContainer
     */
    public function authorize(AuthorizationInterface $authorizationData);

    /**
     * @param PayoneOrderInterface $orderTransfer
     *
     * @return PayonePaymentTransfer
     */
    public function getPayment(PayoneOrderInterface $orderTransfer);

    /**
     * @param CreditCardInterface $creditCardData
     *
     * @return CreditCardCheckResponseContainer
     */
    public function creditCardCheck(CreditCardInterface $creditCardData);

}
