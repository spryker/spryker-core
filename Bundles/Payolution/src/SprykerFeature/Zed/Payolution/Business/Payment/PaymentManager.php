<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business\Payment;

use Generated\Shared\Transfer\PayolutionRequestTransfer;
use SprykerFeature\Zed\Payolution\Business\Api\Adapter\AdapterInterface;
use SprykerFeature\Zed\Payolution\Business\Api\Request\Converter;
use SprykerFeature\Zed\Payolution\Business\Api\Request\ConverterInterface;
use SprykerFeature\Zed\Payolution\Business\Api\Response\AbstractResponse;
use SprykerFeature\Zed\Payolution\Business\Api\Response\PreAuthorizationResponse;
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
     * @var Converter
     */
    private $requestConverter;

    /**
     * @var array
     */
    private $methodMappers = [];

    /**
     * @param AdapterInterface $executionAdapter
     * @param PayolutionQueryContainerInterface $queryContainer
     * @param ConverterInterface $requestConverter
     */
    public function __construct(
        AdapterInterface $executionAdapter,
        PayolutionQueryContainerInterface $queryContainer,
        ConverterInterface $requestConverter
    ) {
        $this->executionAdapter = $executionAdapter;
        $this->queryContainer = $queryContainer;
        $this->requestConverter = $requestConverter;
    }

    /**
     * @param MethodMapperInterface $mapper
     */
    public function registerMethodMapper(MethodMapperInterface $mapper)
    {
        $this->methodMappers[$mapper->getName()] = $mapper;
    }

    /**
     * @param int $idPayment
     *
     * @return PreAuthorizationResponse
     */
    public function preAuthorizePayment($idPayment)
    {
        $paymentEntity = $this->queryContainer->queryPaymentById($idPayment);

        $requestTransfer = $this
            ->methodMappers[MethodMapperInterface::INVOICE]
            ->mapToPreAuthorization($paymentEntity);

        $this->logApiRequest($requestTransfer, $paymentEntity);

        $requestData = $this->requestConverter->toArray($requestTransfer);
        $responseData = $this->executionAdapter->sendArrayDataRequest($requestData);

        $response = new PreAuthorizationResponse();
        $response->initFromArray($responseData);

        $this->logApiResponse($response, $paymentEntity);

        return $response;
    }

    /**
     * @param PayolutionRequestTransfer $request
     *
     * @throws \Propel\Runtime\Exception\PropelException
     */
    private function logApiRequest(PayolutionRequestTransfer $requestTransfer, SpyPaymentPayolution $paymentEntity)
    {
        $logEntity = (new SpyPaymentPayolutionTransactionRequestLog())
            ->setPaymentCode($requestTransfer->getPaymentCode())
            ->setPresentationAmount($requestTransfer->getPresentationAmount())
            ->setPresentationCurrency($requestTransfer->getPresentationCurrency())
            ->setTransactionId($requestTransfer->getIdentificationTransactionid())
            ->setReferenceId($requestTransfer->getIdentificationReferenceid())
            ->setFkPaymentPayolution($paymentEntity->getIdPaymentPayolution());
        $logEntity->save();
    }

    /**
     * @param AbstractResponse $response
     * @param SpyPaymentPayolution $paymentEntity
     * @throws \Propel\Runtime\Exception\PropelException
     */
    private function logApiResponse(AbstractResponse $response, SpyPaymentPayolution $paymentEntity)
    {
        $logEntity = new SpyPaymentPayolutionTransactionStatusLog();
        $logEntity->fromArray($response->toArray());
        $logEntity->setFkPaymentPayolution($paymentEntity->getIdPaymentPayolution());
        $logEntity->save();
    }

}
