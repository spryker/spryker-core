<?php

namespace SprykerFeature\Zed\Payone\Business;


use Generated\Zed\Ide\AutoCompletion;
use Propel\Runtime\Propel;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerFeature\Shared\Payone\Transfer\AuthorizationDataInterface;
use SprykerFeature\Shared\Payone\Transfer\CaptureDataInterface;
use SprykerFeature\Shared\Payone\Transfer\DebitDataInterface;
use SprykerFeature\Shared\Payone\Transfer\RefundDataInterface;
use SprykerFeature\Shared\Payone\Transfer\StandardParameterInterface;
use SprykerFeature\Zed\Payone\Business\Api\Adapter\AdapterInterface;
use SprykerFeature\Zed\Payone\Business\Api\ApiConstants;
use SprykerFeature\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer;
use SprykerFeature\Zed\Payone\Business\Api\Response\Container\AbstractResponseContainer;
use SprykerFeature\Zed\Payone\Business\Api\Response\Container\AuthorizationResponseContainer;
use SprykerFeature\Zed\Payone\Business\Api\Response\Container\CaptureResponseContainer;
use SprykerFeature\Zed\Payone\Business\Api\Response\Container\DebitResponseContainer;
use SprykerFeature\Zed\Payone\Business\Api\Response\Container\PreAuthorizationResponseContainer;
use SprykerFeature\Zed\Payone\Business\SequenceNumber\SequenceNumberProviderInterface;
use SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayone;
use SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayoneApiLog;


class PaymentManager implements ApiConstants
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
     * @var PaymentMethodMapperInterface
     */
    protected $paymentMethodMapper;
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
     * @param LocatorLocatorInterface $locator
     * @param AdapterInterface $executionAdapter
     * @param PaymentMethodMapperInterface $paymentMethodMapper
     * @param StandardParameterInterface $standardParameter
     * @param SequenceNumberProviderInterface $sequenceNumberProvider
     * @param ModeDetectorInterface $modeDetector
     */
    public function __construct(LocatorLocatorInterface $locator,
                                AdapterInterface $executionAdapter,
                                PaymentMethodMapperInterface $paymentMethodMapper,
                                StandardParameterInterface $standardParameter,
                                SequenceNumberProviderInterface $sequenceNumberProvider,
                                ModeDetectorInterface $modeDetector)
    {
        $this->locator = $locator;
        $this->paymentMethodMapper = $paymentMethodMapper;
        $this->executionAdapter = $executionAdapter;
        $this->standardParameter = $standardParameter;
        $this->sequenceNumberProvider = $sequenceNumberProvider;
        $this->modeDetector = $modeDetector;

        // @todo Is it ok to do setter injection, and do it here?
        $this->paymentMethodMapper->setStandardParameter($this->standardParameter);
        $this->paymentMethodMapper->setSequenceNumberProvider($this->sequenceNumberProvider);
    }

    /**
     * @param AuthorizationDataInterface $authorizationData
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function authorize(AuthorizationDataInterface $authorizationData)
    {
        // @todo just works. refactor!
        $requestContainer = $this->paymentMethodMapper->mapAuthorization($authorizationData);
        $this->setStandardParameter($requestContainer);

        $paymentEntity = $this->initializePayment($authorizationData->getPaymentMethod());
        $apiLogEntity = $this->initializeApiLog($requestContainer);

        $rawResponse = $this->executionAdapter->sendRequest($requestContainer);
        \SprykerFeature_Shared_Library_Log::log($rawResponse, 'payone-test.log');
        $responseContainer = new AuthorizationResponseContainer($rawResponse);

        $apiLogEntity->setStatus($responseContainer->getStatus());
        $apiLogEntity->setUserId($responseContainer->getUserid());
        $apiLogEntity->setTransactionId($responseContainer->getTxid());
        $apiLogEntity->save();

        $paymentEntity->setTransactionId($responseContainer->getTxid());
        $paymentEntity->save();

        echo '<pre>' . print_r($responseContainer, false) . '</pre>';die;
    }

    /**
     * @param AuthorizationDataInterface $authorizationData
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function preAuthorize(AuthorizationDataInterface $authorizationData)
    {
        // @todo just works. refactor!
        $requestContainer = $this->paymentMethodMapper->mapPreAuthorization($authorizationData);
        $this->setStandardParameter($requestContainer);

        $paymentEntity = $this->initializePayment($authorizationData->getPaymentMethod());
        $apiLogEntity = $this->initializeApiLog($requestContainer);

        $rawResponse = $this->executionAdapter->sendRequest($requestContainer);
        \SprykerFeature_Shared_Library_Log::log($rawResponse, 'payone-test.log');
        $responseContainer = new PreAuthorizationResponseContainer($rawResponse);

        $apiLogEntity->setStatus($responseContainer->getStatus());
        $apiLogEntity->setUserId($responseContainer->getUserid());
        $apiLogEntity->setTransactionId($responseContainer->getTxid());
        $apiLogEntity->save();

        $paymentEntity->setTransactionId($responseContainer->getTxid());
        $paymentEntity->save();

        echo '<pre>' . print_r($responseContainer, false) . '</pre>';die;
    }

    /**
     * @param CaptureDataInterface $captureData
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function capture(CaptureDataInterface $captureData)
    {
        // @todo just works. refactor!
        $requestContainer = $this->paymentMethodMapper->mapCapture($captureData);
        $this->setStandardParameter($requestContainer);

        $apiLogEntity = $this->initializeApiLog($requestContainer);

        $rawResponse = $this->executionAdapter->sendRequest($requestContainer);
        \SprykerFeature_Shared_Library_Log::log($rawResponse, 'payone-test.log');
        $responseContainer = new CaptureResponseContainer($rawResponse);

        $apiLogEntity->setStatus($responseContainer->getStatus());
        $apiLogEntity->setTransactionId($responseContainer->getTxid());
        $apiLogEntity->save();

        echo '<pre>' . print_r($responseContainer, false) . '</pre>';die;
    }

    /**
     * @param DebitDataInterface $debitData
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function debit(DebitDataInterface $debitData)
    {
        // @todo just works. refactor!
        $requestContainer = $this->paymentMethodMapper->mapDebit($debitData);
        $this->setStandardParameter($requestContainer);

        $apiLogEntity = $this->initializeApiLog($requestContainer);

        $rawResponse = $this->executionAdapter->sendRequest($requestContainer);
        \SprykerFeature_Shared_Library_Log::log($rawResponse, 'payone-test.log');
        $responseContainer = new DebitResponseContainer($rawResponse);

        $apiLogEntity->setStatus($responseContainer->getStatus());
        $apiLogEntity->setTransactionId($responseContainer->getTxid());
        $apiLogEntity->save();

        echo '<pre>' . print_r($responseContainer, false) . '</pre>';die;
    }

    public function refund(RefundDataInterface $refundData)
    {

    }

    /**
     * @param $paymentMethodName
     * @return SpyPaymentPayone
     * @throws \Propel\Runtime\Exception\PropelException
     */
    protected function initializePayment($paymentMethodName)
    {
        $entity = $this->locator->payone()->entitySpyPaymentPayone();
        $entity->setPaymentMethod($paymentMethodName);
        $entity->save();

        return $entity;
    }

    /**
     * @param AbstractRequestContainer $container
     * @return SpyPaymentPayoneApiLog
     * @throws \Propel\Runtime\Exception\PropelException
     */
    protected function initializeApiLog(AbstractRequestContainer $container)
    {
        $entity = $this->locator->payone()->entitySpyPaymentPayoneApiLog();
        $entity->setRequest($container->getRequest());
        $entity->setMode($container->getMode());
        $entity->setMerchantId($container->getMid());
        $entity->setPortalId($container->getPortalid());
        $entity->save();

        return $entity;
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

        // @todo does spryker want to send integrator/solution data???
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
