<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business\Payment;

use Generated\Shared\Transfer\PayolutionRequestTransfer;
use Generated\Shared\Transfer\PayolutionResponseTransfer;
use SprykerFeature\Zed\Payolution\Business\Api\Adapter\AdapterInterface;
use SprykerFeature\Zed\Payolution\Business\Api\Request\ConverterInterface as RequestConverterInterface;
use SprykerFeature\Zed\Payolution\Business\Api\Response\ConverterInterface as ResponseConverterInterface;
use SprykerFeature\Zed\Payolution\Business\Payment\MethodMapper\MethodMapperInterface;
use SprykerFeature\Zed\Payolution\Persistence\PayolutionQueryContainerInterface;
use SprykerFeature\Zed\Payolution\Persistence\Propel\SpyPaymentPayolution;
use SprykerFeature\Zed\Payolution\Persistence\Propel\SpyPaymentPayolutionTransactionRequestLog;
use SprykerFeature\Zed\Payolution\Persistence\Propel\SpyPaymentPayolutionTransactionStatusLog;

class PaymentManager implements PaymentManagerInterface
{

    /**
     * @var AdapterInterface
     */
    protected $executionAdapter;

    /**
     * @var PayolutionQueryContainerInterface
     */
    private $queryContainer;

    /**
     * @var RequestConverterInterface
     */
    private $requestConverter;

    /**
     * @var ResponseConverterInterface
     */
    private $responseConverter;

    /**
     * @var array
     */
    private $methodMappers = [];

    /**
     * @param AdapterInterface $executionAdapter
     * @param PayolutionQueryContainerInterface $queryContainer
     * @param RequestConverterInterface $requestConverter
     * @param ResponseConverterInterface $responseConverter
     */
    public function __construct(
        AdapterInterface $executionAdapter,
        PayolutionQueryContainerInterface $queryContainer,
        RequestConverterInterface $requestConverter,
        ResponseConverterInterface $responseConverter
    ) {
        $this->executionAdapter = $executionAdapter;
        $this->queryContainer = $queryContainer;
        $this->requestConverter = $requestConverter;
        $this->responseConverter = $responseConverter;
    }

    /**
     * @param MethodMapperInterface $mapper
     */
    public function registerMethodMapper(MethodMapperInterface $mapper)
    {
        $this->methodMappers[$mapper->getAccountBrand()] = $mapper;
    }

    /**
     * @param int $idPayment
     *
     * @return PayolutionResponseTransfer
     */
    public function preAuthorizePayment($idPayment)
    {
        $paymentEntity = $this->getPaymentEntity($idPayment);
        $requestTransfer = $this->getMethodMapper($paymentEntity)->mapToPreAuthorization($paymentEntity);

        return $this->sendRequest($requestTransfer, $paymentEntity);
    }

    /**
     * @param int $idPayment
     *
     * @return PayolutionResponseTransfer
     */
    public function reAuthorizePayment($idPayment)
    {
        $paymentEntity = $this->getPaymentEntity($idPayment);
        $statusLogEntity = $this->getLatestTransactionStatusLogItem($idPayment);

        $requestTransfer = $this
            ->getMethodMapper($paymentEntity)
            ->mapToReAuthorization($paymentEntity, $statusLogEntity->getIdentificationUniqueid());

        return $this->sendRequest($requestTransfer, $paymentEntity);
    }

    /**
     * @param int $idPayment
     *
     * @return PayolutionResponseTransfer
     */
    public function capturePayment($idPayment)
    {
        $paymentEntity = $this->getPaymentEntity($idPayment);
        $statusLogEntity = $this->getLatestTransactionStatusLogItem($idPayment);

        $requestTransfer = $this
            ->getMethodMapper($paymentEntity)
            ->mapToCapture($paymentEntity, $statusLogEntity->getIdentificationUniqueid());

        return $this->sendRequest($requestTransfer, $paymentEntity);
    }

    /**
     * @param int $idPayment
     *
     * @return SpyPaymentPayolution
     */
    private function getPaymentEntity($idPayment)
    {
        return $this->queryContainer->queryPaymentById($idPayment);
    }

    /**
     * @param SpyPaymentPayolution $paymentEntity
     *
     * @return MethodMapperInterface
     */
    private function getMethodMapper(SpyPaymentPayolution $paymentEntity)
    {
        return $this->methodMappers[$paymentEntity->getAccountBrand()];
    }

    /**
     * @param int $idPayment
     *
     * @return SpyPaymentPayolutionTransactionStatusLog
     */
    private function getLatestTransactionStatusLogItem($idPayment)
    {
        return $this
            ->queryContainer
            ->queryTransactionStatusLogByPaymentIdLatestFirst($idPayment)
            ->findOne();
    }

    /**
     * @param PayolutionRequestTransfer $requestTransfer
     * @param SpyPaymentPayolution $paymentEntity
     *
     * @return PayolutionResponseTransfer
     */
    private function sendRequest(PayolutionRequestTransfer $requestTransfer, SpyPaymentPayolution $paymentEntity)
    {
        $this->logApiRequest($requestTransfer, $paymentEntity);

        $requestData = $this->requestConverter->toArray($requestTransfer);
        $responseData = $this->executionAdapter->sendArrayDataRequest($requestData);
        $responseTransfer = $this->responseConverter->fromArray($responseData);

        $this->logApiResponse($responseTransfer, $paymentEntity);

        return $responseTransfer;
    }

    /**
     * @param PayolutionRequestTransfer $requestTransfer
     * @param SpyPaymentPayolution $paymentEntity
     *
     * @throws \Propel\Runtime\Exception\PropelException
     */
    private function logApiRequest(PayolutionRequestTransfer $requestTransfer, SpyPaymentPayolution $paymentEntity)
    {
        (new SpyPaymentPayolutionTransactionRequestLog())
            ->setPaymentCode($requestTransfer->getPaymentCode())
            ->setPresentationAmount($requestTransfer->getPresentationAmount())
            ->setPresentationCurrency($requestTransfer->getPresentationCurrency())
            ->setTransactionId($requestTransfer->getIdentificationTransactionid())
            ->setReferenceId($requestTransfer->getIdentificationReferenceid())
            ->setFkPaymentPayolution($paymentEntity->getIdPaymentPayolution())
            ->save();
    }

    /**
     * @param PayolutionResponseTransfer $responseTransfer
     * @param SpyPaymentPayolution $paymentEntity
     *
     * @throws \Propel\Runtime\Exception\PropelException
     */
    private function logApiResponse(PayolutionResponseTransfer $responseTransfer, SpyPaymentPayolution $paymentEntity)
    {
        $logEntity = new SpyPaymentPayolutionTransactionStatusLog();
        $logEntity->fromArray($responseTransfer->toArray());
        $logEntity
            ->setFkPaymentPayolution($paymentEntity->getIdPaymentPayolution())
            ->save();
    }

}
