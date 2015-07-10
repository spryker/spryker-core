<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payone\Business\Payment;

use Generated\Shared\Payone\AuthorizationInterface;
use Generated\Shared\Payone\CaptureInterface;
use Generated\Shared\Payone\DebitInterface;
use Generated\Shared\Payone\RefundInterface;
use Generated\Shared\Payone\StandardParameterInterface;
use SprykerFeature\Zed\Payone\Business\Api\Request\Container\AuthorizationContainer;
use SprykerFeature\Zed\Payone\Business\Api\Request\Container\DebitContainer;
use SprykerFeature\Zed\Payone\Business\Api\Request\Container\RefundContainer;
use SprykerFeature\Zed\Payone\Business\Api\Request\Container\CaptureContainer;
use SprykerFeature\Zed\Payone\Business\SequenceNumber\SequenceNumberProviderInterface;

interface PaymentMethodMapperInterface
{

    /**
     * @return string
     */
    public function getName();

    /**
     * @param SequenceNumberProviderInterface $sequenceNumberProvider
     */
    public function setSequenceNumberProvider(SequenceNumberProviderInterface $sequenceNumberProvider);

    /**
     * @param StandardParameterInterface $standardParameter
     */
    public function setStandardParameter(StandardParameterInterface $standardParameter);

    /**
     * @param AuthorizationInterface $authorizationData
     *
     * @return AuthorizationContainer
     */
    public function mapAuthorization(AuthorizationInterface $authorizationData);

    /**
     * @param AuthorizationInterface $authorizationData
     *
     * @return AuthorizationContainer
     */
    public function mapPreAuthorization(AuthorizationInterface $authorizationData);

    /**
     * @param CaptureInterface $captureData
     *
     * @return CaptureContainer
     */
    public function mapCapture(CaptureInterface $captureData);

    /**
     * @param DebitInterface $debitData
     *
     * @return DebitContainer
     */
    public function mapDebit(DebitInterface $debitData);

    /**
     * @param RefundInterface $refundData
     *
     * @return RefundContainer
     */
    public function mapRefund(RefundInterface $refundData);

}
