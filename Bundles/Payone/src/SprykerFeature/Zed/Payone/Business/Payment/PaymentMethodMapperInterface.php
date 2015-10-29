<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payone\Business\Payment;

use Generated\Shared\Payone\PayoneStandardParameterInterface;
use SprykerFeature\Zed\Payone\Business\Api\Request\Container\AuthorizationContainer;
use SprykerFeature\Zed\Payone\Business\Api\Request\Container\PreAuthorizationContainer;
use SprykerFeature\Zed\Payone\Business\Api\Request\Container\CaptureContainer;
use SprykerFeature\Zed\Payone\Business\SequenceNumber\SequenceNumberProviderInterface;
use Orm\Zed\Payone\Persistence\SpyPaymentPayone;

interface PaymentMethodMapperInterface
{

    /**
     * @return string
     */
    public function getName();

    /**
     * @param SequenceNumberProviderInterface $sequenceNumberProvider
     *
     * @return void
     */
    public function setSequenceNumberProvider(SequenceNumberProviderInterface $sequenceNumberProvider);

    /**
     * @param PayoneStandardParameterInterface $standardParameter
     *
     * @return void
     */
    public function setStandardParameter(PayoneStandardParameterInterface $standardParameter);

    /**
     * @param SpyPaymentPayone $paymentEntity
     *
     * @return AuthorizationContainer
     */
    public function mapPaymentToAuthorization(SpyPaymentPayone $paymentEntity);

    /**
     * @param SpyPaymentPayone $paymentEntity
     *
     * @return PreAuthorizationContainer
     */
    public function mapPaymentToPreAuthorization(SpyPaymentPayone $paymentEntity);

    /**
     * @param SpyPaymentPayone $paymentEntity
     *
     * @return CaptureContainer
     */
    public function mapPaymentToCapture(SpyPaymentPayone $paymentEntity);

    /**
     * @param SpyPaymentPayone $paymentEntity
     *
     * @return CaptureContainer
     */
    public function mapPaymentToDebit(SpyPaymentPayone $paymentEntity);

    /**
     * @param SpyPaymentPayone $paymentEntity
     *
     * @return CaptureContainer
     */
    public function mapPaymentToRefund(SpyPaymentPayone $paymentEntity);

}
