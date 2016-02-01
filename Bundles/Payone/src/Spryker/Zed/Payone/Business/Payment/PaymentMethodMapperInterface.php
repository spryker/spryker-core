<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Payone\Business\Payment;

use Generated\Shared\Transfer\PayoneStandardParameterTransfer;
use Spryker\Zed\Payone\Business\Api\Request\Container\AuthorizationContainer;
use Spryker\Zed\Payone\Business\Api\Request\Container\PreAuthorizationContainer;
use Spryker\Zed\Payone\Business\Api\Request\Container\CaptureContainer;
use Spryker\Zed\Payone\Business\SequenceNumber\SequenceNumberProviderInterface;
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
     * @param PayoneStandardParameterTransfer $standardParameter
     *
     * @return void
     */
    public function setStandardParameter(PayoneStandardParameterTransfer $standardParameter);

    /**
     * @param SpyPaymentPayone $paymentEntity
     *
     * @return \Spryker\Zed\Payone\Business\Api\Request\Container\AuthorizationContainer
     */
    public function mapPaymentToAuthorization(SpyPaymentPayone $paymentEntity);

    /**
     * @param SpyPaymentPayone $paymentEntity
     *
     * @return \Spryker\Zed\Payone\Business\Api\Request\Container\PreAuthorizationContainer
     */
    public function mapPaymentToPreAuthorization(SpyPaymentPayone $paymentEntity);

    /**
     * @param SpyPaymentPayone $paymentEntity
     *
     * @return \Spryker\Zed\Payone\Business\Api\Request\Container\CaptureContainer
     */
    public function mapPaymentToCapture(SpyPaymentPayone $paymentEntity);

    /**
     * @param SpyPaymentPayone $paymentEntity
     *
     * @return \Spryker\Zed\Payone\Business\Api\Request\Container\CaptureContainer
     */
    public function mapPaymentToDebit(SpyPaymentPayone $paymentEntity);

    /**
     * @param SpyPaymentPayone $paymentEntity
     *
     * @return \Spryker\Zed\Payone\Business\Api\Request\Container\CaptureContainer
     */
    public function mapPaymentToRefund(SpyPaymentPayone $paymentEntity);

}
