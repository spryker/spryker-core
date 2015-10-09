<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business\Payment;

use Generated\Shared\Payolution\CheckoutRequestInterface;
use Generated\Shared\Payolution\PayolutionRequestInterface;
use Generated\Shared\Payolution\PayolutionResponseInterface;
use Generated\Shared\Transfer\PayolutionResponseTransfer;
use SprykerFeature\Zed\Payolution\Business\Api\Adapter\AdapterInterface;
use SprykerFeature\Zed\Payolution\Business\Api\Request\ConverterInterface as RequestConverterInterface;
use SprykerFeature\Zed\Payolution\Business\Api\Response\ConverterInterface as ResponseConverterInterface;
use SprykerFeature\Zed\Payolution\Business\Payment\MethodMapper\MethodMapperInterface;
use SprykerFeature\Zed\Payolution\Persistence\PayolutionQueryContainerInterface;
use SprykerFeature\Zed\Payolution\Persistence\Propel\SpyPaymentPayolution;
use SprykerFeature\Zed\Payolution\Persistence\Propel\SpyPaymentPayolutionTransactionRequestLog;
use SprykerFeature\Zed\Payolution\Persistence\Propel\SpyPaymentPayolutionTransactionStatusLog;

class Communicator implements CommunicatorInterface
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
     * @var MethodMapperInterface[]
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
     * @param CheckoutRequestInterface $checkoutRequestTransfer
     *
     * @return PayolutionResponseInterface
     */
    public function preCheckPayment(CheckoutRequestInterface $checkoutRequestTransfer)
    {
        $paymentTransfer = $checkoutRequestTransfer->getPayolutionPayment();
        $requestTransfer = $this
            ->getMethodMapper($paymentTransfer->getAccountBrand())
            ->mapToPreCheck($checkoutRequestTransfer);

        $responseTransfer = $this->sendRequest($requestTransfer);

        return $responseTransfer;
    }

    /**
     * @param int $idPayment
     *
     * @return PayolutionResponseInterface
     */
    public function preAuthorizePayment($idPayment)
    {
        $paymentEntity = $this->getPaymentEntity($idPayment);
        $requestTransfer = $this
            ->getMethodMapper($paymentEntity->getAccountBrand())
            ->mapToPreAuthorization($paymentEntity);

        return $this->sendLoggedRequest($requestTransfer, $paymentEntity);
    }

    /**
     * @param int $idPayment
     *
     * @return PayolutionResponseInterface
     */
    public function reAuthorizePayment($idPayment)
    {
        $paymentEntity = $this->getPaymentEntity($idPayment);
        $statusLogEntity = $this->getLatestTransactionStatusLogItem($idPayment);

        $requestTransfer = $this
            ->getMethodMapper($paymentEntity->getAccountBrand())
            ->mapToReAuthorization($paymentEntity, $statusLogEntity->getIdentificationUniqueid());

        return $this->sendLoggedRequest($requestTransfer, $paymentEntity);
    }

    /**
     * @param int $idPayment
     *
     * @return PayolutionRequestInterface
     */
    public function revertPayment($idPayment)
    {
        $paymentEntity = $this->getPaymentEntity($idPayment);
        $statusLogEntity = $this->getLatestTransactionStatusLogItem($idPayment);

        $requestTransfer = $this
            ->getMethodMapper($paymentEntity->getAccountBrand())
            ->mapToReversal($paymentEntity, $statusLogEntity->getIdentificationUniqueid());

        return $this->sendLoggedRequest($requestTransfer, $paymentEntity);
    }

    /**
     * @param int $idPayment
     *
     * @return PayolutionResponseInterface
     */
    public function capturePayment($idPayment)
    {
        $paymentEntity = $this->getPaymentEntity($idPayment);
        $statusLogEntity = $this->getLatestTransactionStatusLogItem($idPayment);

        $requestTransfer = $this
            ->getMethodMapper($paymentEntity->getAccountBrand())
            ->mapToCapture($paymentEntity, $statusLogEntity->getIdentificationUniqueid());

        return $this->sendLoggedRequest($requestTransfer, $paymentEntity);
    }

    /**
     * @param int $idPayment
     *
     * @return PayolutionResponseInterface
     */
    public function refundPayment($idPayment)
    {
        $paymentEntity = $this->getPaymentEntity($idPayment);
        $statusLogEntity = $this->getLatestTransactionStatusLogItem($idPayment);

        $requestTransfer = $this
            ->getMethodMapper($paymentEntity->getAccountBrand())
            ->mapToRefund($paymentEntity, $statusLogEntity->getIdentificationUniqueid());

        return $this->sendLoggedRequest($requestTransfer, $paymentEntity);
    }

    /**
     * @param int $idPayment
     *
     * @return SpyPaymentPayolution
     */
    private function getPaymentEntity($idPayment)
    {
        return $this->queryContainer->queryPaymentById($idPayment)->findOne();
    }

    /**
     * @param string $accountBrand
     *
     * @return MethodMapperInterface
     */
    private function getMethodMapper($accountBrand)
    {
        return $this->methodMappers[$accountBrand];
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
     * @param PayolutionRequestInterface $requestTransfer
     * @param SpyPaymentPayolution $paymentEntity
     *
     * @return PayolutionResponseTransfer
     */
    private function sendLoggedRequest(PayolutionRequestInterface $requestTransfer, SpyPaymentPayolution $paymentEntity)
    {
        $this->logApiRequest($requestTransfer, $paymentEntity->getIdPaymentPayolution());
        $responseTransfer = $this->sendRequest($requestTransfer);
        $this->logApiResponse($responseTransfer, $paymentEntity->getIdPaymentPayolution());

        return $responseTransfer;
    }

    /**
     * @param PayolutionRequestInterface $requestTransfer
     * @param int $idPayment
     *
     * @return SpyPaymentPayolutionTransactionRequestLog
     */
    private function logApiRequest(PayolutionRequestInterface $requestTransfer, $idPayment)
    {
        $logEntity = new SpyPaymentPayolutionTransactionRequestLog();
        $logEntity
            ->setPaymentCode($requestTransfer->getPaymentCode())
            ->setPresentationAmount($requestTransfer->getPresentationAmount())
            ->setPresentationCurrency($requestTransfer->getPresentationCurrency())
            ->setTransactionId($requestTransfer->getIdentificationTransactionid())
            ->setReferenceId($requestTransfer->getIdentificationReferenceid())
            ->setFkPaymentPayolution($idPayment);
        $logEntity->save();

        return $logEntity;
    }

    /**
     * @param PayolutionRequestInterface $requestTransfer
     *
     * @return PayolutionResponseInterface
     */
    private function sendRequest(PayolutionRequestInterface $requestTransfer)
    {
        $requestData = $this->requestConverter->toArray($requestTransfer);
        $responseData = $this->executionAdapter->sendArrayDataRequest($requestData);
        $responseTransfer = $this->responseConverter->fromArray($responseData);

        return $responseTransfer;
    }

    /**
     * @param PayolutionResponseInterface $responseTransfer
     * @param int $idPayment
     */
    private function logApiResponse(PayolutionResponseInterface $responseTransfer, $idPayment)
    {
        $logEntity = new SpyPaymentPayolutionTransactionStatusLog();
        $logEntity->fromArray($responseTransfer->toArray());
        $logEntity->setFkPaymentPayolution($idPayment);
        $logEntity->save();
    }

}
