<?php

namespace SprykerFeature\Zed\Payone\Business;


use SprykerFeature\Shared\Payone\Transfer\AuthorizationDataInterface;
use SprykerFeature\Shared\Payone\Transfer\CaptureDataInterface;
use SprykerFeature\Shared\Payone\Transfer\DebitDataInterface;
use SprykerFeature\Shared\Payone\Transfer\RefundDataInterface;
use SprykerFeature\Shared\Payone\Transfer\StandardParameterInterface;
use SprykerFeature\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer;
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
     * @param StandardParameterInterface $standardParamter
     */
    public function setStandardParameter(StandardParameterInterface $standardParamter);

    /**
     * @param AuthorizationDataInterface $authorizationData
     * @return AbstractRequestContainer
     */
    public function mapAuthorization(AuthorizationDataInterface $authorizationData);

    /**
     * @param AuthorizationDataInterface $authorizationData
     * @return AbstractRequestContainer
     */
    public function mapPreAuthorization(AuthorizationDataInterface $authorizationData);

    /**
     * @param CaptureDataInterface $captureData
     * @return AbstractRequestContainer
     */
    public function mapCapture(CaptureDataInterface $captureData);

    /**
     * @param DebitDataInterface $debitData
     * @return AbstractRequestContainer
     */
    public function mapDebit(DebitDataInterface $debitData);

    /**
     * @param RefundDataInterface $refundData
     * @return AbstractRequestContainer
     */
    public function mapRefund(RefundDataInterface $refundData);

}
