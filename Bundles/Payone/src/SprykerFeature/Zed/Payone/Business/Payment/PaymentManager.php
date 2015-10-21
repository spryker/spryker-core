<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payone\Business\Payment;

use Generated\Shared\Checkout\CheckoutResponseInterface;
use Generated\Shared\Payone\PayoneCreditCardInterface;
use Generated\Shared\Payone\PayoneRefundInterface;
use Generated\Shared\Payone\PayoneStandardParameterInterface;
use Generated\Shared\Payone\OrderInterface;
use Generated\Shared\Refund\PaymentDataInterface;
use Generated\Shared\Transfer\PaymentDataTransfer;
use Generated\Shared\Transfer\PayoneCreditCardCheckRequestDataTransfer;
use Generated\Shared\Transfer\PaymentDetailTransfer;
use Generated\Shared\Transfer\PayonePaymentLogTransfer;
use Generated\Shared\Transfer\PayonePaymentTransfer;
use Propel\Runtime\Collection\ObjectCollection;
use SprykerFeature\Shared\Payone\PayoneApiConstants;
use SprykerFeature\Shared\Payone\Dependency\ModeDetectorInterface;
use SprykerFeature\Zed\Library\Copy;
use SprykerFeature\Zed\Payone\Business\Api\Call\CreditCardCheck;
use SprykerFeature\Zed\Payone\Business\Api\Request\Container\DebitContainer;
use SprykerFeature\Zed\Payone\Business\Api\Request\Container\RefundContainer;
use SprykerFeature\Zed\Payone\Business\Exception\InvalidPaymentMethodException;
use SprykerFeature\Zed\Payone\Business\Api\Adapter\AdapterInterface;
use SprykerFeature\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer;
use SprykerFeature\Zed\Payone\Business\Api\Request\Container\AuthorizationContainerInterface;
use SprykerFeature\Zed\Payone\Business\Api\Response\Container\AuthorizationResponseContainer;
use SprykerFeature\Zed\Payone\Business\Api\Response\Container\CaptureResponseContainer;
use SprykerFeature\Zed\Payone\Business\Api\Response\Container\DebitResponseContainer;
use SprykerFeature\Zed\Payone\Business\Api\Response\Container\CreditCardCheckResponseContainer;
use SprykerFeature\Zed\Payone\Business\Api\Response\Container\RefundResponseContainer;
use SprykerFeature\Zed\Payone\Business\Key\HashGenerator;
use SprykerFeature\Zed\Payone\Business\SequenceNumber\SequenceNumberProviderInterface;
use SprykerFeature\Zed\Payone\Persistence\PayoneQueryContainerInterface;
use SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayone;
use SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayoneApiLog;
use Propel\Runtime\Exception\PropelException;
use SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayoneTransactionStatusLog;

class PaymentManager implements PaymentManagerInterface
{

    const LOG_TYPE_API_LOG = 'SpyPaymentPayoneApiLog';
    const LOG_TYPE_TRANSACTION_STATUS_LOG = 'SpyPaymentPayoneTransactionStatusLog';

    /**
     * @var AdapterInterface
     */
    protected $executionAdapter;

    /**
     * @var PayoneQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var PayoneStandardParameterInterface
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
     * @param AdapterInterface $executionAdapter
     * @param PayoneQueryContainerInterface $queryContainer
     * @param PayoneStandardParameterInterface $standardParameter
     * @param HashGenerator $hashGenerator
     * @param SequenceNumberProviderInterface $sequenceNumberProvider
     * @param ModeDetectorInterface $modeDetector
     */
    public function __construct(
        AdapterInterface $executionAdapter,
        PayoneQueryContainerInterface $queryContainer,
        PayoneStandardParameterInterface $standardParameter,
        HashGenerator $hashGenerator,
        SequenceNumberProviderInterface $sequenceNumberProvider,
        ModeDetectorInterface $modeDetector)
    {
        $this->executionAdapter = $executionAdapter;
        $this->queryContainer = $queryContainer;
        $this->standardParameter = $standardParameter;
        $this->hashGenerator = $hashGenerator;
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
     * @return PaymentMethodMapperInterface|null
     */
    protected function findPaymentMethodMapperByName($name)
    {
        if (array_key_exists($name, $this->registeredMethodMappers)) {
            return $this->registeredMethodMappers[$name];
        }

        return null;
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
     * @param int $idPayment
     *
     * @return AuthorizationResponseContainer
     */
    public function authorizePayment($idPayment)
    {
        $paymentEntity = $this->getPaymentEntity($idPayment);
        $paymentMethodMapper = $this->getPaymentMethodMapper($paymentEntity);
        $requestContainer = $paymentMethodMapper->mapPaymentToAuthorization($paymentEntity);
        $responseContainer = $this->performAuthorizationRequest($paymentEntity, $requestContainer);

        return $responseContainer;
    }

    /**
     * @param int $idPayment
     *
     * @return AuthorizationResponseContainer
     */
    public function preAuthorizePayment($idPayment)
    {
        $paymentEntity = $this->getPaymentEntity($idPayment);
        $paymentMethodMapper = $this->getPaymentMethodMapper($paymentEntity);
        $requestContainer = $paymentMethodMapper->mapPaymentToPreAuthorization($paymentEntity);
        $responseContainer = $this->performAuthorizationRequest($paymentEntity, $requestContainer);

        return $responseContainer;
    }

    /**
     * @param SpyPaymentPayone $paymentEntity
     * @param AuthorizationContainerInterface $requestContainer
     *
     * @return AuthorizationResponseContainer
     */
    protected function performAuthorizationRequest(SpyPaymentPayone $paymentEntity, AuthorizationContainerInterface $requestContainer)
    {
        $this->setStandardParameter($requestContainer);

        $apiLogEntity = $this->initializeApiLog($paymentEntity, $requestContainer);
        $rawResponse = $this->executionAdapter->sendRequest($requestContainer);
        $responseContainer = new AuthorizationResponseContainer($rawResponse);
        $this->updatePaymentAfterAuthorization($paymentEntity, $responseContainer);
        $this->updateApiLogAfterAuthorization($apiLogEntity, $responseContainer);

        return $responseContainer;
    }

    /**
     * @param SpyPaymentPayone $paymentEntity
     *
     * @return PaymentMethodMapperInterface
     */
    protected function getPaymentMethodMapper(SpyPaymentPayone $paymentEntity)
    {
        return $this->getRegisteredPaymentMethodMapper($paymentEntity->getPaymentMethod());
    }

    /**
     * @param int $idPayment
     *
     * @return SpyPaymentPayone
     */
    protected function getPaymentEntity($idPayment)
    {
        return $this->queryContainer->getPaymentById($idPayment)->findOne();
    }

    /**
     * @param int $idPayment
     *
     * @return CaptureResponseContainer
     */
    public function capturePayment($idPayment)
    {
        $paymentEntity = $this->getPaymentEntity($idPayment);
        $paymentMethodMapper = $this->getPaymentMethodMapper($paymentEntity);

        $requestContainer = $paymentMethodMapper->mapPaymentToCapture($paymentEntity);
        $this->setStandardParameter($requestContainer);

        $apiLogEntity = $this->initializeApiLog($paymentEntity, $requestContainer);

        $rawResponse = $this->executionAdapter->sendRequest($requestContainer);
        $responseContainer = new CaptureResponseContainer($rawResponse);

        $this->updateApiLogAfterCapture($apiLogEntity, $responseContainer);

        return $responseContainer;
    }

    /**
     * @param int $idPayment
     *
     * @return DebitResponseContainer
     */
    public function debitPayment($idPayment)
    {
        $paymentEntity = $this->getPaymentEntity($idPayment);
        $paymentMethodMapper = $this->getPaymentMethodMapper($paymentEntity);
        $requestContainer = $paymentMethodMapper->mapPaymentToDebit($paymentEntity);
        $this->setStandardParameter($requestContainer);

        $paymentEntity = $this->findPaymentByTransactionId($paymentEntity->getTransactionId());
        $apiLogEntity = $this->initializeApiLog($paymentEntity, $requestContainer);

        $rawResponse = $this->executionAdapter->sendRequest($requestContainer);
        $responseContainer = new DebitResponseContainer($rawResponse);

        $this->updateApiLogAfterDebit($apiLogEntity, $responseContainer);

        return $responseContainer;
    }

    /**
     * @param PayoneCreditCardInterface $creditCardData
     *
     * @return CreditCardCheckResponseContainer
     */
    public function creditCardCheck(PayoneCreditCardInterface $creditCardData)
    {
        $paymentMethodMapper = $this->getRegisteredPaymentMethodMapper($creditCardData->getPayment()->getPaymentMethod());
        $requestContainer = $paymentMethodMapper->mapCreditCardCheck($creditCardData);
        $this->setStandardParameter($requestContainer);

        $rawResponse = $this->executionAdapter->sendRequest($requestContainer);
        $responseContainer = new CreditCardCheckResponseContainer($rawResponse);

        return $responseContainer;
    }

    /**
     * @param PayoneRefundInterface $refundTransfer
     *
     * @return RefundResponseContainer
     */
    public function refundPayment(PayoneRefundInterface $refundTransfer)
    {
        $payonePaymentTransfer = $refundTransfer->getPayment();

        $paymentEntity = $this->getPaymentEntity($payonePaymentTransfer->getFkSalesOrder());
        $paymentMethodMapper = $this->getPaymentMethodMapper($paymentEntity);
        $requestContainer = $paymentMethodMapper->mapPaymentToRefund($paymentEntity);
        $requestContainer->setAmount($refundTransfer->getAmount());
        $this->setStandardParameter($requestContainer);

        $apiLogEntity = $this->initializeApiLog($paymentEntity, $requestContainer);

        $rawResponse = $this->executionAdapter->sendRequest($requestContainer);
        $responseContainer = new RefundResponseContainer($rawResponse);

        $this->updateApiLogAfterRefund($apiLogEntity, $responseContainer);

        return $responseContainer;
    }

    /**
     * @param OrderInterface $orderTransfer
     *
     * @return PayonePaymentTransfer
     */
    protected function getPayment(OrderInterface $orderTransfer)
    {
        $payment = $this->queryContainer->getPaymentByOrderId($orderTransfer->getIdSalesOrder())->findOne();
        $paymentDetail = $payment->getSpyPaymentPayoneDetail();

        $paymentDetailTransfer = new PaymentDetailTransfer();
        $paymentDetailTransfer->fromArray($paymentDetail->toArray(), true);

        $paymentTransfer = new PayonePaymentTransfer();
        $paymentTransfer->fromArray($payment->toArray(), true);
        $paymentTransfer->setPaymentDetail($paymentDetailTransfer);

        return $paymentTransfer;
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
        if ($container instanceof RefundContainer || $container instanceof DebitContainer) {
            $entity->setSequenceNumber($container->getSequenceNumber());
        }
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
        $container->setKey($this->hashGenerator->hash($this->standardParameter->getKey()));
        $container->setMid($this->standardParameter->getMid());
        $container->setPortalid($this->standardParameter->getPortalId());
        $container->setMode($this->modeDetector->getMode());
    }

    /**
     * @param int $idOrder
     *
     * @return PaymentDataInterface
     */
    public function getPaymentData($idOrder)
    {
        $paymentEntity = $this->queryContainer->getPaymentByOrderId($idOrder)->findOne();
        $paymentDetailEntity = $paymentEntity->getSpyPaymentPayoneDetail();
        $paymentDataTransfer = new PaymentDataTransfer();
        $paymentDataTransfer->fromArray($paymentDetailEntity->toArray(), true);

        return $paymentDataTransfer;
    }

    /**
     * Gets payment logs (both api and transaction status) for specific orders in chronological order.
     *
     * @param ObjectCollection $orders
     *
     * @return PayonePaymentLogTransfer[]
     */
    public function getPaymentLogs(ObjectCollection $orders)
    {
        $apiLogs = $this->queryContainer->getApiLogsByOrderIds($orders)->find()->getData();

        $transactionStatusLogs = $this->queryContainer->getTransactionStatusLogsByOrderIds($orders)->find()->getData();

        $logs = [];
        /** @var SpyPaymentPayoneApiLog $apiLog */
        foreach ($apiLogs as $apiLog) {
            $key = $apiLog->getCreatedAt()->format('Y-m-d\TH:i:s\Z') . 'a' . $apiLog->getIdPaymentPayoneApiLog();
            $payonePaymentLogTransfer = new PayonePaymentLogTransfer();
            $payonePaymentLogTransfer->fromArray($apiLog->toArray(), true);
            $payonePaymentLogTransfer->setLogType(self::LOG_TYPE_API_LOG);
            $logs[$key] = $payonePaymentLogTransfer;
        }
        /** @var SpyPaymentPayoneTransactionStatusLog $transactionStatusLog */
        foreach ($transactionStatusLogs as $transactionStatusLog) {
            $key = $transactionStatusLog->getCreatedAt()->format('Y-m-d\TH:i:s\Z') . 't' . $transactionStatusLog->getIdPaymentPayoneTransactionStatusLog();
            $payonePaymentLogTransfer = new PayonePaymentLogTransfer();
            $payonePaymentLogTransfer->fromArray($transactionStatusLog->toArray(), true);
            $payonePaymentLogTransfer->setLogType(self::LOG_TYPE_TRANSACTION_STATUS_LOG);
            $logs[$key] = $payonePaymentLogTransfer;
        }

        ksort($logs);

        return $logs;
    }

    /**
     * @param PayoneCreditCardCheckRequestDataTransfer $creditCardCheckRequestDataTransfer
     *
     * @return array
     */
    public function getCreditCardCheckRequestData(PayoneCreditCardCheckRequestDataTransfer $creditCardCheckRequestDataTransfer)
    {
        $this->standardParameter->fromArray($creditCardCheckRequestDataTransfer->toArray(), true);

        $creditCardCheck = new CreditCardCheck($this->standardParameter, $this->hashGenerator, $this->modeDetector);

        $data = $creditCardCheck->mapCreditCardCheckData();

        return $data->toArray();
    }

    /**
     * @param OrderInterface $orderTransfer
     *
     * @return bool
     */
    public function isRefundPossible(OrderInterface $orderTransfer)
    {
        $paymentTransfer = $this->getPayment($orderTransfer);

        if (!$this->isPaymentDataRequired($orderTransfer)) {
            return true;
        }

        $paymentDetailTransfer = $paymentTransfer->getPaymentDetail();

        return $paymentDetailTransfer->getBic() && $paymentDetailTransfer->getIban();
    }

    /**
     * @param OrderInterface $orderTransfer
     *
     * @return bool
     */
    public function isPaymentDataRequired(OrderInterface $orderTransfer)
    {
        $paymentTransfer = $this->getPayment($orderTransfer);

        // Return early if we don't need the iban or bic data
        $paymentMethod = $paymentTransfer->getPaymentMethod();
        $whiteList = [
            PayoneApiConstants::PAYMENT_METHOD_PAYPAL,
            PayoneApiConstants::PAYMENT_METHOD_CREDITCARD_PSEUDO,
        ];

        if (in_array($paymentMethod, $whiteList)) {
            return false;
        }

        return true;
    }

    /**
     * @param OrderInterface $orderTransfer
     * @param CheckoutResponseInterface $checkoutResponse
     *
     * @return CheckoutResponseInterface
     */
    public function postSaveHook(OrderInterface $orderTransfer, CheckoutResponseInterface $checkoutResponse)
    {
        $apiLogsQuery = $this->queryContainer->getLastApiLogsByOrderId($orderTransfer->getIdSalesOrder());
        $apiLog = $apiLogsQuery->findOne();

        if ($apiLog) {
            $redirectUrl = $apiLog->getRedirectUrl();

            if ($redirectUrl !== null) {
                $checkoutResponse->setIsExternalRedirect(true);
                $checkoutResponse->setRedirectUrl($redirectUrl);
            }
        }

        return $checkoutResponse;
    }

    /**
     * @param PaymentDataTransfer $paymentDataTransfer
     * @param int $idOrder
     *
     * @return void
     */
    public function updatePaymentDetail(PaymentDataTransfer $paymentDataTransfer, $idOrder)
    {
        $paymentEntity = $this->queryContainer->getPaymentByOrderId($idOrder)->findOne();
        $paymentDetailEntity = $paymentEntity->getSpyPaymentPayoneDetail();

        Copy::transferToEntity($paymentDataTransfer, $paymentDetailEntity);

        $paymentDetailEntity->save();
    }

}
