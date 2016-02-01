<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Payone\Business\Payment;

use Generated\Shared\Transfer\PayoneStandardParameterTransfer;
use Spryker\Zed\Payone\Business\SequenceNumber\SequenceNumberProviderInterface;
use Orm\Zed\Payone\Persistence\SpyPaymentPayone;

interface PaymentMethodMapperInterface
{

    /**
     * @return string
     */
    public function getName();

    /**
     * @param \Spryker\Zed\Payone\Business\SequenceNumber\SequenceNumberProviderInterface $sequenceNumberProvider
     *
     * @return void
     */
    public function setSequenceNumberProvider(SequenceNumberProviderInterface $sequenceNumberProvider);

    /**
     * @param \Generated\Shared\Transfer\PayoneStandardParameterTransfer $standardParameter
     *
     * @return void
     */
    public function setStandardParameter(PayoneStandardParameterTransfer $standardParameter);

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentEntity
     *
     * @return \Spryker\Zed\Payone\Business\Api\Request\Container\AuthorizationContainer
     */
    public function mapPaymentToAuthorization(SpyPaymentPayone $paymentEntity);

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentEntity
     *
     * @return \Spryker\Zed\Payone\Business\Api\Request\Container\PreAuthorizationContainer
     */
    public function mapPaymentToPreAuthorization(SpyPaymentPayone $paymentEntity);

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentEntity
     *
     * @return \Spryker\Zed\Payone\Business\Api\Request\Container\CaptureContainer
     */
    public function mapPaymentToCapture(SpyPaymentPayone $paymentEntity);

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentEntity
     *
     * @return \Spryker\Zed\Payone\Business\Api\Request\Container\CaptureContainer
     */
    public function mapPaymentToDebit(SpyPaymentPayone $paymentEntity);

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentEntity
     *
     * @return \Spryker\Zed\Payone\Business\Api\Request\Container\CaptureContainer
     */
    public function mapPaymentToRefund(SpyPaymentPayone $paymentEntity);

}
