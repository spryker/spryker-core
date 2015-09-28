<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business\Payment;

use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\PayolutionRequestTransfer;
use Generated\Shared\Transfer\PayolutionResponseTransfer;
use SprykerEngine\Zed\Kernel\Business\DependencyContainer\DependencyContainerInterface;
use SprykerFeature\Zed\Payolution\Business\Api\Adapter\AdapterInterface;
use SprykerFeature\Zed\Payolution\Business\Api\Request\ConverterInterface as RequestConverterInterface;
use SprykerFeature\Zed\Payolution\Business\Api\Response\ConverterInterface as ResponseConverterInterface;
use SprykerFeature\Zed\Payolution\Business\Payment\MethodMapper\MethodMapperInterface;
use SprykerFeature\Zed\Payolution\Business\PayolutionDependencyContainer;
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
     * @var DependencyContainerInterface
     */
    private $dependencyContainer;

    /**
     * @var MethodMapperInterface[]
     */
    private $methodMappers = [];

    /**
     * @param AdapterInterface $executionAdapter
     * @param PayolutionQueryContainerInterface $queryContainer
     * @param RequestConverterInterface $requestConverter
     * @param ResponseConverterInterface $responseConverter
     * @param DependencyContainerInterface $dependencyContainer
     */
    public function __construct(
        AdapterInterface $executionAdapter,
        PayolutionQueryContainerInterface $queryContainer,
        RequestConverterInterface $requestConverter,
        ResponseConverterInterface $responseConverter,
        DependencyContainerInterface $dependencyContainer
    ) {
        $this->executionAdapter = $executionAdapter;
        $this->queryContainer = $queryContainer;
        $this->requestConverter = $requestConverter;
        $this->responseConverter = $responseConverter;
        $this->dependencyContainer = $dependencyContainer;
    }

    /**
     * @param MethodMapperInterface $mapper
     */
    public function registerMethodMapper(MethodMapperInterface $mapper)
    {
        $this->methodMappers[$mapper->getAccountBrand()] = $mapper;
    }

    /**
     * @param CheckoutRequestTransfer $checkoutRequestTransfer
     *
     * @return PayolutionResponseTransfer
     */
    public function preCheckPayment(CheckoutRequestTransfer $checkoutRequestTransfer)
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
     * @return PayolutionResponseTransfer
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
     * @return PayolutionResponseTransfer
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
     * @return PayolutionRequestTransfer
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
     * @return PayolutionResponseTransfer
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
     * @return PayolutionResponseTransfer
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
     * @param PayolutionRequestTransfer $requestTransfer
     * @param SpyPaymentPayolution $paymentEntity
     *
     * @return PayolutionResponseTransfer
     */
    private function sendLoggedRequest(PayolutionRequestTransfer $requestTransfer, SpyPaymentPayolution $paymentEntity)
    {
        $this->logApiRequest($requestTransfer, $paymentEntity->getIdPaymentPayolution());
        $responseTransfer = $this->sendRequest($requestTransfer);
        $this->logApiResponse($responseTransfer, $paymentEntity->getIdPaymentPayolution());

        return $responseTransfer;
    }

    /**
     * @param PayolutionRequestTransfer $requestTransfer
     * @param int $idPayment
     *
     * @return SpyPaymentPayolutionTransactionRequestLog
     */
    private function logApiRequest(PayolutionRequestTransfer $requestTransfer, $idPayment)
    {
        $logEntity = $this->getDependencyContainer()->createTransactionRequestLogEntity();
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
     * @param PayolutionRequestTransfer $requestTransfer
     *
     * @return PayolutionResponseTransfer
     */
    private function sendRequest(PayolutionRequestTransfer $requestTransfer)
    {
        $requestData = $this->requestConverter->toArray($requestTransfer);
        $responseData = $this->executionAdapter->sendArrayDataRequest($requestData);
        $responseTransfer = $this->responseConverter->fromArray($responseData);

        return $responseTransfer;
    }

    /**
     * @param PayolutionResponseTransfer $responseTransfer
     * @param int $idPayment
     */
    private function logApiResponse(PayolutionResponseTransfer $responseTransfer, $idPayment)
    {
        $logEntity = $this->getDependencyContainer()->createTransactionStatusLogEntity();
        $logEntity->fromArray($responseTransfer->toArray());
        $logEntity->setFkPaymentPayolution($idPayment);
        $logEntity->save();
    }

    /**
     * @return PayolutionDependencyContainer
     */
    private function getDependencyContainer()
    {
        return $this->dependencyContainer;
    }

}
