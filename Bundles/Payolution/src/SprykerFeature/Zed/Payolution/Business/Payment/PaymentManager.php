<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business\Payment;

use SprykerFeature\Zed\Payolution\Business\Api\Adapter\AdapterInterface;
use SprykerFeature\Zed\Payolution\Business\Api\Request\AbstractRequest;
use SprykerFeature\Zed\Payolution\Business\Api\Response\AbstractResponse;
use SprykerFeature\Zed\Payolution\Business\Api\Response\PreAuthorizationResponse;
use SprykerFeature\Zed\Payolution\Persistence\PayolutionQueryContainerInterface;
use SprykerFeature\Zed\Payolution\Persistence\Propel\SpyPaymentPayolution;
use SprykerFeature\Zed\Payolution\Persistence\Propel\SpyPaymentPayolutionApiLog;
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
     * @var array
     */
    private $methodMappers = [];

    public function __construct(
        AdapterInterface $executionAdapter,
        PayolutionQueryContainerInterface $queryContainer
    ) {
        $this->executionAdapter = $executionAdapter;
        $this->queryContainer = $queryContainer;
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

        $authorizationRequest = $this
            ->methodMappers[MethodMapperInterface::INVOICE]
            ->mapToPreAuthorization($paymentEntity);

        $this->logApiRequest($authorizationRequest, $paymentEntity);
        $authorizationResponse = $this->executionAdapter->sendRequest($authorizationRequest);

        $response = new PreAuthorizationResponse();
        $response->initFromArray($authorizationResponse);
        $this->logApiResponse($response, $paymentEntity);

        return $response;
    }

    /**
     * @param AbstractRequest $request
     * @throws \Propel\Runtime\Exception\PropelException
     */
    private function logApiRequest(AbstractRequest $request, SpyPaymentPayolution $paymentEntity)
    {
        $logEntity = new SpyPaymentPayolutionTransactionRequestLog();
        $logEntity->setPaymentCode($request->getPaymentCode());
        $logEntity->setPresentationAmount($request->getPresentationAmount());
        $logEntity->setPresentationCurrency($request->getPresentationCurrency());
        $logEntity->setTransactionId($request->getIdentificationTransactionId());
        $logEntity->setReferenceId($request->getIdentifactionReferenceId());
        $logEntity->setFkPaymentPayolution($paymentEntity->getIdPaymentPayolution());

        $logEntity->save();
    }

    private function logApiResponse(AbstractResponse $response, SpyPaymentPayolution $paymentEntity)
    {
        $logEntity = new SpyPaymentPayolutionTransactionStatusLog();
        $logEntity->fromArray($response->toArray());
        $logEntity->setFkPaymentPayolution($paymentEntity->getIdPaymentPayolution());

        $logEntity->save();

    }
}
