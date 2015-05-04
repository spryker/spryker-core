<?php

namespace SprykerFeature\Zed\Payone\Business\Payment;


use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerFeature\Shared\Payone\Dependency\AuthorizationDataInterface;
use SprykerFeature\Shared\Payone\Dependency\CaptureDataInterface;
use SprykerFeature\Shared\Payone\Dependency\DebitDataInterface;
use SprykerFeature\Zed\Payone\Business\Payment\PaymentMethodMapperInterface;
use SprykerFeature\Shared\Payone\Dependency\RefundDataInterface;
use SprykerFeature\Shared\Payone\Dependency\StandardParameterInterface;
use SprykerFeature\Zed\Payone\Business\Api\Adapter\AdapterInterface;
use SprykerFeature\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer;
use SprykerFeature\Zed\Payone\Business\Api\Request\Container\AuthorizationContainer;
use SprykerFeature\Zed\Payone\Business\Api\Response\Container\AuthorizationResponseContainer;
use SprykerFeature\Zed\Payone\Business\Api\Response\Container\CaptureResponseContainer;
use SprykerFeature\Zed\Payone\Business\Api\Response\Container\DebitResponseContainer;
use SprykerFeature\Zed\Payone\Business\Api\Response\Container\RefundResponseContainer;
use SprykerFeature\Zed\Payone\Business\Mode\ModeDetectorInterface;
use SprykerFeature\Zed\Payone\Business\SequenceNumber\SequenceNumberProviderInterface;
use SprykerFeature\Zed\Payone\Persistence\PayoneQueryContainerInterface;
use SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayone;
use SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayoneApiLog;
use \Propel\Runtime\Exception\PropelException;


class PaymentManager
{

    /**
     * @var AutoCompletion
     */
    protected $locator;
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
     * @param LocatorLocatorInterface $locator
     * @param AdapterInterface $executionAdapter
     * @param PayoneQueryContainerInterface $queryContainer
     * @param PaymentMethodMapperInterface $paymentMethodMapper
     * @param StandardParameterInterface $standardParameter
     * @param SequenceNumberProviderInterface $sequenceNumberProvider
     * @param ModeDetectorInterface $modeDetector
     */
    public function __construct(
        LocatorLocatorInterface $locator,
        AdapterInterface $executionAdapter,
        PayoneQueryContainerInterface $queryContainer,
        StandardParameterInterface $standardParameter,
        SequenceNumberProviderInterface $sequenceNumberProvider,
        ModeDetectorInterface $modeDetector)
    {
        $this->locator = $locator;
        $this->executionAdapter = $executionAdapter;
        $this->queryContainer = $queryContainer;
        $this->standardParameter = $standardParameter;
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
     * @return null|PaymentMethodMapperInterface
     */
    public function findPaymentMethodMapperByName($name)
    {
        if (array_key_exists($name, $this->registeredMethodMappers)) {
            return $this->registeredMethodMappers[$name];
        }

        return null;
    }

    /**
     * @param AuthorizationDataInterface $authorizationData
     * @return AuthorizationResponseContainer
     */
    public function authorize(AuthorizationDataInterface $authorizationData)
    {
        $paymentMethodMapper = $this->findPaymentMethodMapperByName($authorizationData->getPayment()->getPaymentMethod());
        $requestContainer = $paymentMethodMapper->mapAuthorization($authorizationData);
        $responseContainer = $this->performAuthorization($authorizationData, $requestContainer);

        return $responseContainer;
    }

    /**
     * @param AuthorizationDataInterface $authorizationData
     * @return AuthorizationResponseContainer
     */
    public function preAuthorize(AuthorizationDataInterface $authorizationData)
    {
        $paymentMethodMapper = $this->findPaymentMethodMapperByName($authorizationData->getPayment()->getPaymentMethod());
        $requestContainer = $paymentMethodMapper->mapPreAuthorization($authorizationData);
        $responseContainer = $this->performAuthorization($authorizationData, $requestContainer);

        return $responseContainer;
    }

    /**
     * @param AuthorizationDataInterface $authorizationData
     * @param AuthorizationContainer $requestContainer
     * @return AuthorizationResponseContainer
     */
    protected function performAuthorization(AuthorizationDataInterface $authorizationData, AuthorizationContainer $requestContainer)
    {
        // @todo just works. refactor! does too much! create PersistenceManagerInterface for db storage?
        $this->setStandardParameter($requestContainer);

        $paymentEntity = $this->initializePayment($authorizationData->getPaymentMethod());
        $apiLogEntity = $this->initializeApiLog($paymentEntity, $requestContainer);

        $rawResponse = $this->executionAdapter->sendRequest($requestContainer);
        \SprykerFeature_Shared_Library_Log::log($rawResponse, 'payone-test.log');
        $responseContainer = new AuthorizationResponseContainer($rawResponse);

        $this->updatePaymentAfterAuthorization($paymentEntity, $responseContainer);
        $this->updateApiLogAfterAuthorization($apiLogEntity, $responseContainer);

        return $responseContainer;
    }

    /**
     * @param CaptureDataInterface $captureData
     * @return CaptureResponseContainer
     */
    public function capture(CaptureDataInterface $captureData)
    {
        $paymentMethodMapper = $this->findPaymentMethodMapperByName($captureData->getPayment()->getPaymentMethod());
        $requestContainer = $paymentMethodMapper->mapCapture($captureData);
        $this->setStandardParameter($requestContainer);

        $paymentEntity = $this->findPaymentByTransactionId($captureData->getPayment()->getTransactionId());
        $apiLogEntity = $this->initializeApiLog($paymentEntity, $requestContainer);

        $rawResponse = $this->executionAdapter->sendRequest($requestContainer);
        \SprykerFeature_Shared_Library_Log::log($rawResponse, 'payone-test.log');
        $responseContainer = new CaptureResponseContainer($rawResponse);

        $this->updateApiLogAfterCapture($apiLogEntity, $responseContainer);

        return $responseContainer;
    }

    /**
     * @param DebitDataInterface $debitData
     * @return DebitResponseContainer
     */
    public function debit(DebitDataInterface $debitData)
    {
        // @todo just works. refactor!
        $paymentMethodMapper = $this->findPaymentMethodMapperByName($debitData->getPayment()->getPaymentMethod());
        $requestContainer = $paymentMethodMapper->mapDebit($debitData);
        $this->setStandardParameter($requestContainer);

        $paymentEntity = $this->findPaymentByTransactionId($debitData->getPayment()->getTransactionId());
        $apiLogEntity = $this->initializeApiLog($paymentEntity, $requestContainer);

        $rawResponse = $this->executionAdapter->sendRequest($requestContainer);
        \SprykerFeature_Shared_Library_Log::log($rawResponse, 'payone-test.log');
        $responseContainer = new DebitResponseContainer($rawResponse);

        $this->updateApiLogAfterDebit($apiLogEntity, $responseContainer);

        return $responseContainer;
    }

    /**
     * @param RefundDataInterface $refundData
     * @return RefundResponseContainer
     */
    public function refund(RefundDataInterface $refundData)
    {
        $paymentMethodMapper = $this->findPaymentMethodMapperByName($refundData->getPayment()->getPaymentMethod());
        $requestContainer = $paymentMethodMapper->mapDebit($refundData);
        $this->setStandardParameter($requestContainer);

        $paymentEntity = $this->findPaymentByTransactionId($refundData->getPayment()->getTransactionId());
        $apiLogEntity = $this->initializeApiLog($paymentEntity, $requestContainer);

        $rawResponse = $this->executionAdapter->sendRequest($requestContainer);
        \SprykerFeature_Shared_Library_Log::log($rawResponse, 'payone-test.log');
        $responseContainer = new RefundResponseContainer($rawResponse);

        $this->updateApiLogAfterRefund($apiLogEntity, $responseContainer);

        return $responseContainer;
    }

    /**
     * @param string $paymentMethodName
     * @return SpyPaymentPayone
     * @throws PropelException
     */
    protected function initializePayment($paymentMethodName)
    {
        $entity = $this->locator->payone()->entitySpyPaymentPayone();
        $entity->setPaymentMethod($paymentMethodName);
        $entity->save();

        return $entity;
    }

    /**
     * @param SpyPaymentPayone $paymentEntity
     * @param AuthorizationResponseContainer $responseContainer
     * @throws PropelException
     */
    protected function updatePaymentAfterAuthorization(SpyPaymentPayone $paymentEntity, AuthorizationResponseContainer $responseContainer)
    {
        $paymentEntity->setTransactionId($responseContainer->getTxid());
        $paymentEntity->save();
    }

    /**
     * @param string $transactionId
     * @return SpyPaymentPayone
     */
    protected function findPaymentByTransactionId($transactionId)
    {
        return $this->queryContainer->getPaymentByTransactionIdQuery($transactionId)->findOne();
    }

    /**
     * @param SpyPaymentPayone $paymentEntity
     * @param AbstractRequestContainer $container
     * @return SpyPaymentPayoneApiLog
     * @throws PropelException
     */
    protected function initializeApiLog(SpyPaymentPayone $paymentEntity, AbstractRequestContainer $container)
    {
        $entity = $this->locator->payone()->entitySpyPaymentPayoneApiLog();
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
     * @throws \Propel\Runtime\Exception\PropelException
     */
    protected function updateApiLogAfterRefund(SpyPaymentPayoneApiLog $apiLogEntity, RefundResponseContainer $responseContainer)
    {
        $apiLogEntity->setStatus($responseContainer->getStatus());
        $apiLogEntity->setTransactionId($responseContainer->getTxid());
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
        $container->setEncoding($this->standardParameter->getEncoding());
        $container->setKey($this->hashKey($this->standardParameter->getKey()));
        $container->setMid($this->standardParameter->getMid());
        $container->setPortalid($this->standardParameter->getPortalId());
        $container->setMode($this->modeDetector->getMode());

        // @todo does spryker want to send integrator/solution and version data???
        /*
        $container->setIntegratorName();
        $container->setIntegratorVersion();
        $container->setSolutionName();
        $container->setSolutionVersion();
        */
    }

    /**
     * @param string $key
     * @return string
     */
    protected function hashKey($key)
    {
        return hash('md5', $key);
    }

}
