<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payone\Business\Payment;

use Generated\Shared\Payone\AuthorizationInterface;
use Generated\Shared\Payone\CaptureInterface;
use Generated\Shared\Payone\DebitInterface;
use Generated\Shared\Payone\RefundInterface;
use Generated\Shared\Payone\CreditCardInterface;
use Generated\Shared\Payone\StandardParameterInterface;
use Generated\Shared\Payone\OrderInterface as PayoneOrderInterface;
use Generated\Shared\Transfer\PayonePaymentTransfer;
use SprykerFeature\Shared\Payone\PayoneApiConstants;
use SprykerFeature\Shared\Payone\Dependency\HashInterface;
use SprykerFeature\Shared\Payone\Dependency\ModeDetectorInterface;
use SprykerFeature\Zed\Payone\Business\Exception\InvalidPaymentMethodException;
use SprykerFeature\Zed\Payone\Business\Api\Adapter\AdapterInterface;
use SprykerFeature\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer;
use SprykerFeature\Zed\Payone\Business\Api\Request\Container\AuthorizationContainerInterface;
use SprykerFeature\Zed\Payone\Business\Api\Response\Container\AuthorizationResponseContainer;
use SprykerFeature\Zed\Payone\Business\Api\Response\Container\CaptureResponseContainer;
use SprykerFeature\Zed\Payone\Business\Api\Response\Container\DebitResponseContainer;
use SprykerFeature\Zed\Payone\Business\Api\Response\Container\CreditCardCheckResponseContainer;
use SprykerFeature\Zed\Payone\Business\Api\Response\Container\RefundResponseContainer;
use SprykerFeature\Zed\Payone\Business\SequenceNumber\SequenceNumberProviderInterface;
use SprykerFeature\Zed\Payone\Persistence\PayoneQueryContainerInterface;
use SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayone;
use SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayoneApiLog;
use Propel\Runtime\Exception\PropelException;

class PaymentManager implements PaymentManagerInterface
{

    /**
     * @var AdapterInterface
     */
    protected $executionAdapter;
    /**
     * @var PayoneQueryContainerInterface
     */
    protected $queryContainer;
    /**
     * @var StandardParameterInterface
     */
    protected $standardParameter;
    /**
     * @var HashInterface
     */
    protected $hashProvider;
    /**
     * @var SequenceNumberProviderInterface
     */
    protected $sequenceNumberProvider;
    /**
     * @var ModeDetectorInterface
     */
    protected $modeDetector;
    /**
     * @var PaymentMethodMapperInterface[]
     */
    protected $registeredMethodMappers;

    /**
     * @param AdapterInterface $executionAdapter
     * @param PayoneQueryContainerInterface $queryContainer
     * @param StandardParameterInterface $standardParameter
     * @param HashInterface $hashProvider
     * @param SequenceNumberProviderInterface $sequenceNumberProvider
     * @param ModeDetectorInterface $modeDetector
     */
    public function __construct(
        AdapterInterface $executionAdapter,
        PayoneQueryContainerInterface $queryContainer,
        StandardParameterInterface $standardParameter,
        HashInterface $hashProvider,
        SequenceNumberProviderInterface $sequenceNumberProvider,
        ModeDetectorInterface $modeDetector)
    {
        $this->executionAdapter = $executionAdapter;
        $this->queryContainer = $queryContainer;
        $this->standardParameter = $standardParameter;
        $this->hashProvider = $hashProvider;
        $this->sequenceNumberProvider = $sequenceNumberProvider;
        $this->modeDetector = $modeDetector;
    }

    /**
     * @param PaymentMethodMapperInterface $paymentMethodMapper
     */
    public function registerPaymentMethodMapper(PaymentMethodMapperInterface $paymentMethodMapper)
    {
        $paymentMethodMapper->setStandardParameter($this->standardParameter);
        $paymentMethodMapper->setSequenceNumberProvider($this->sequenceNumberProvider);
        $this->registeredMethodMappers[$paymentMethodMapper->getName()] = $paymentMethodMapper;
    }

    /**
     * @param string $name
     *
     * @return null|PaymentMethodMapperInterface
     */
    protected function findPaymentMethodMapperByName($name)
    {
        if (array_key_exists($name, $this->registeredMethodMappers)) {
            return $this->registeredMethodMappers[$name];
        }

        return;
    }

    /**
     * @param string $paymentMethodName
     *
     * @throws InvalidPaymentMethodException
     *
     * @return PaymentMethodMapperInterface
     */
    protected function getRegisteredPaymentMethodMapper($paymentMethodName)
    {
        $paymentMethodMapper = $this->findPaymentMethodMapperByName($paymentMethodName);
        if (null === $paymentMethodMapper) {
            throw new InvalidPaymentMethodException(
                sprintf('No registered payment method mapper found for given method name %s', $paymentMethodName)
            );
        }

        return $paymentMethodMapper;
    }

    /**
     * @param AuthorizationInterface $authorizationData
     *
     * @return AuthorizationResponseContainer
     */
    public function authorize(AuthorizationInterface $authorizationData)
    {
        $paymentMethodMapper = $this->getRegisteredPaymentMethodMapper($authorizationData->getPaymentMethod());
        $requestContainer = $paymentMethodMapper->mapAuthorization($authorizationData);
        $responseContainer = $this->performAuthorization($authorizationData, $requestContainer);

        return $responseContainer;
    }

    /**
     * @param AuthorizationInterface $authorizationData
     *
     * @return AuthorizationResponseContainer
     */
    public function preAuthorize(AuthorizationInterface $authorizationData)
    {
        $paymentMethodMapper = $this->getRegisteredPaymentMethodMapper($authorizationData->getPaymentMethod());
        $requestContainer = $paymentMethodMapper->mapPreAuthorization($authorizationData);
        $responseContainer = $this->performAuthorization($authorizationData, $requestContainer);

        return $responseContainer;
    }

    /**
     * @param AuthorizationInterface $authorizationData
     * @param AuthorizationContainerInterface $requestContainer
     *
     * @return AuthorizationResponseContainer
     */
    protected function performAuthorization(AuthorizationInterface $authorizationData, AuthorizationContainerInterface $requestContainer)
    {
        $this->setStandardParameter($requestContainer);

        $paymentEntity = $this->initializePayment(
            $authorizationData->getPaymentMethod(),
            $requestContainer->getRequest()
        );
        $apiLogEntity = $this->initializeApiLog($paymentEntity, $requestContainer);

        $rawResponse = $this->executionAdapter->sendRequest($requestContainer);
        $responseContainer = new AuthorizationResponseContainer($rawResponse);

        $this->updatePaymentAfterAuthorization($paymentEntity, $responseContainer);
        $this->updateApiLogAfterAuthorization($apiLogEntity, $responseContainer);

        return $responseContainer;
    }

    /**
     * @param CaptureInterface $captureData
     *
     * @return CaptureResponseContainer
     */
    public function capture(CaptureInterface $captureData)
    {
        $paymentMethodMapper = $this->getRegisteredPaymentMethodMapper($captureData->getPayment()->getPaymentMethod());
        $requestContainer = $paymentMethodMapper->mapCapture($captureData);
        $this->setStandardParameter($requestContainer);

        $paymentEntity = $this->findPaymentByTransactionId($captureData->getPayment()->getTransactionId());
        $apiLogEntity = $this->initializeApiLog($paymentEntity, $requestContainer);

        $rawResponse = $this->executionAdapter->sendRequest($requestContainer);
        $responseContainer = new CaptureResponseContainer($rawResponse);

        $this->updateApiLogAfterCapture($apiLogEntity, $responseContainer);

        return $responseContainer;
    }

    /**
     * @param DebitInterface $debitData
     *
     * @return DebitResponseContainer
     */
    public function debit(DebitInterface $debitData)
    {
        $paymentMethodMapper = $this->getRegisteredPaymentMethodMapper($debitData->getPayment()->getPaymentMethod());
        $requestContainer = $paymentMethodMapper->mapDebit($debitData);
        $this->setStandardParameter($requestContainer);

        $paymentEntity = $this->findPaymentByTransactionId($debitData->getPayment()->getTransactionId());
        $apiLogEntity = $this->initializeApiLog($paymentEntity, $requestContainer);

        $rawResponse = $this->executionAdapter->sendRequest($requestContainer);
        $responseContainer = new DebitResponseContainer($rawResponse);

        $this->updateApiLogAfterDebit($apiLogEntity, $responseContainer);

        return $responseContainer;
    }

    /**
     * @param CreditCardInterface $creditCardData
     *
     * @return CreditCardCheckResponseContainer
     */
    public function creditCardCheck(CreditCardInterface $creditCardData)
    {
        $paymentMethodMapper = $this->getRegisteredPaymentMethodMapper($creditCardData->getPayment()->getPaymentMethod());
        $requestContainer = $paymentMethodMapper->mapCreditCardCheck($creditCardData);
        $this->setStandardParameter($requestContainer);

        $rawResponse = $this->executionAdapter->sendRequest($requestContainer);
        $responseContainer = new CreditCardCheckResponseContainer($rawResponse);

        return $responseContainer;
    }

    /**
     * @param RefundInterface $refundData
     *
     * @return RefundResponseContainer
     */
    public function refund(RefundInterface $refundData)
    {
        $paymentMethodMapper = $this->getRegisteredPaymentMethodMapper($refundData->getPayment()->getPaymentMethod());
        $requestContainer = $paymentMethodMapper->mapRefund($refundData);
        $this->setStandardParameter($requestContainer);

        $paymentEntity = $this->findPaymentByTransactionId($refundData->getPayment()->getTransactionId());
        $apiLogEntity = $this->initializeApiLog($paymentEntity, $requestContainer);

        $rawResponse = $this->executionAdapter->sendRequest($requestContainer);
        $responseContainer = new RefundResponseContainer($rawResponse);

        $this->updateApiLogAfterRefund($apiLogEntity, $responseContainer);

        return $responseContainer;
    }

    /**
     * @param PayoneOrderInterface $orderTransfer
     *
     * @return PayonePaymentTransfer
     */
    public function getPayment(PayoneOrderInterface $orderTransfer)
    {
        $payment = $this->queryContainer->getPaymentByOrderId($orderTransfer->getIdSalesOrder())->findOne();

        $paymentTransfer = new PayonePaymentTransfer();
        $paymentTransfer->fromArray($payment->toArray());

        return $paymentTransfer;
    }

    /**
     * @param $paymentMethodName
     * @param $authorizationType
     *
     * @return SpyPaymentPayone
     */
    protected function initializePayment($paymentMethodName, $authorizationType)
    {
        $entity = $entity = new SpyPaymentPayone();
        $entity->setPaymentMethod($paymentMethodName);
        $entity->setAuthorizationType($authorizationType);
        $entity->save();

        return $entity;
    }

    /**
     * @param SpyPaymentPayone $paymentEntity
     * @param AuthorizationResponseContainer $responseContainer
     *
     * @throws PropelException
     */
    protected function updatePaymentAfterAuthorization(SpyPaymentPayone $paymentEntity, AuthorizationResponseContainer $responseContainer)
    {
        $paymentEntity->setTransactionId($responseContainer->getTxid());
        $paymentEntity->save();
    }

    /**
     * @param string $transactionId
     *
     * @return SpyPaymentPayone
     */
    protected function findPaymentByTransactionId($transactionId)
    {
        return $this->queryContainer->getPaymentByTransactionIdQuery($transactionId)->findOne();
    }

    /**
     * @param SpyPaymentPayone $paymentEntity
     * @param AbstractRequestContainer $container
     *
     * @throws PropelException
     *
     * @return SpyPaymentPayoneApiLog
     */
    protected function initializeApiLog(SpyPaymentPayone $paymentEntity, AbstractRequestContainer $container)
    {
        $entity = new SpyPaymentPayoneApiLog();
        $entity->setSpyPaymentPayone($paymentEntity);
        $entity->setRequest($container->getRequest());
        $entity->setMode($container->getMode());
        $entity->setMerchantId($container->getMid());
        $entity->setPortalId($container->getPortalid());
        $entity->save();

        return $entity;
    }

    /**
     * @param SpyPaymentPayoneApiLog $apiLogEntity
     * @param AuthorizationResponseContainer $responseContainer
     *
     * @throws PropelException
     */
    protected function updateApiLogAfterAuthorization(SpyPaymentPayoneApiLog $apiLogEntity, AuthorizationResponseContainer $responseContainer)
    {
        $apiLogEntity->setStatus($responseContainer->getStatus());
        $apiLogEntity->setUserId($responseContainer->getUserid());
        $apiLogEntity->setTransactionId($responseContainer->getTxid());
        $apiLogEntity->setErrorMessageInternal($responseContainer->getErrormessage());
        $apiLogEntity->setErrorMessageUser($responseContainer->getCustomermessage());
        $apiLogEntity->setErrorCode($responseContainer->getErrorcode());
        $apiLogEntity->setRedirectUrl($responseContainer->getRedirecturl());
        $apiLogEntity->save();
    }

    /**
     * @param SpyPaymentPayoneApiLog $apiLogEntity
     * @param CaptureResponseContainer $responseContainer
     *
     * @throws PropelException
     */
    protected function updateApiLogAfterCapture(SpyPaymentPayoneApiLog $apiLogEntity, CaptureResponseContainer $responseContainer)
    {
        $apiLogEntity->setStatus($responseContainer->getStatus());
        $apiLogEntity->setTransactionId($responseContainer->getTxid());
        $apiLogEntity->setErrorMessageInternal($responseContainer->getErrormessage());
        $apiLogEntity->setErrorMessageUser($responseContainer->getCustomermessage());
        $apiLogEntity->setErrorCode($responseContainer->getErrorcode());
        $apiLogEntity->save();
    }

    /**
     * @param SpyPaymentPayoneApiLog $apiLogEntity
     * @param DebitResponseContainer $responseContainer
     *
     * @throws PropelException
     */
    protected function updateApiLogAfterDebit(SpyPaymentPayoneApiLog $apiLogEntity, DebitResponseContainer $responseContainer)
    {
        $apiLogEntity->setStatus($responseContainer->getStatus());
        $apiLogEntity->setTransactionId($responseContainer->getTxid());
        $apiLogEntity->setErrorMessageInternal($responseContainer->getErrormessage());
        $apiLogEntity->setErrorMessageUser($responseContainer->getCustomermessage());
        $apiLogEntity->setErrorCode($responseContainer->getErrorcode());
        $apiLogEntity->save();
    }

    /**
     * @param SpyPaymentPayoneApiLog $apiLogEntity
     * @param RefundResponseContainer $responseContainer
     *
     * @throws \Propel\Runtime\Exception\PropelException
     */
    protected function updateApiLogAfterRefund(SpyPaymentPayoneApiLog $apiLogEntity, RefundResponseContainer $responseContainer)
    {
        $apiLogEntity->setTransactionId($responseContainer->getTxid());
        $apiLogEntity->setStatus($responseContainer->getStatus());
        $apiLogEntity->setErrorMessageInternal($responseContainer->getErrormessage());
        $apiLogEntity->setErrorMessageUser($responseContainer->getCustomermessage());
        $apiLogEntity->setErrorCode($responseContainer->getErrorcode());
        $apiLogEntity->save();
    }

    /**
     * @param AbstractRequestContainer $container
     */
    protected function setStandardParameter(AbstractRequestContainer $container)
    {
        $container->setApiVersion(PayoneApiConstants::API_VERSION_3_9);
        $container->setEncoding($this->standardParameter->getEncoding());
        $container->setKey($this->hashProvider->hash($this->standardParameter->getKey()));
        $container->setMid($this->standardParameter->getMid());
        $container->setPortalid($this->standardParameter->getPortalId());
        $container->setMode($this->modeDetector->getMode());
    }

}
