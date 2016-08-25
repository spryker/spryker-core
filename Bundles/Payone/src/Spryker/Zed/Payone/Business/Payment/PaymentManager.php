<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payone\Business\Payment;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PaymentDataTransfer;
use Generated\Shared\Transfer\PaymentDetailTransfer;
use Generated\Shared\Transfer\PayoneCreditCardCheckRequestDataTransfer;
use Generated\Shared\Transfer\PayoneCreditCardTransfer;
use Generated\Shared\Transfer\PayonePaymentLogTransfer;
use Generated\Shared\Transfer\PayonePaymentTransfer;
use Generated\Shared\Transfer\PayoneRefundTransfer;
use Generated\Shared\Transfer\PayoneStandardParameterTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Payone\Persistence\SpyPaymentPayone;
use Orm\Zed\Payone\Persistence\SpyPaymentPayoneApiLog;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Shared\Payone\Dependency\ModeDetectorInterface;
use Spryker\Shared\Payone\PayoneApiConstants;
use Spryker\Zed\Payone\Business\Api\Adapter\AdapterInterface;
use Spryker\Zed\Payone\Business\Api\Call\CreditCardCheck;
use Spryker\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer;
use Spryker\Zed\Payone\Business\Api\Request\Container\AuthorizationContainerInterface;
use Spryker\Zed\Payone\Business\Api\Request\Container\DebitContainer;
use Spryker\Zed\Payone\Business\Api\Request\Container\RefundContainer;
use Spryker\Zed\Payone\Business\Api\Response\Container\AuthorizationResponseContainer;
use Spryker\Zed\Payone\Business\Api\Response\Container\CaptureResponseContainer;
use Spryker\Zed\Payone\Business\Api\Response\Container\CreditCardCheckResponseContainer;
use Spryker\Zed\Payone\Business\Api\Response\Container\DebitResponseContainer;
use Spryker\Zed\Payone\Business\Api\Response\Container\RefundResponseContainer;
use Spryker\Zed\Payone\Business\Exception\InvalidPaymentMethodException;
use Spryker\Zed\Payone\Business\Key\HashGenerator;
use Spryker\Zed\Payone\Business\SequenceNumber\SequenceNumberProviderInterface;
use Spryker\Zed\Payone\Persistence\PayoneQueryContainerInterface;

class PaymentManager implements PaymentManagerInterface
{

    const LOG_TYPE_API_LOG = 'SpyPaymentPayoneApiLog';
    const LOG_TYPE_TRANSACTION_STATUS_LOG = 'SpyPaymentPayoneTransactionStatusLog';

    /**
     * @var \Spryker\Zed\Payone\Business\Api\Adapter\AdapterInterface
     */
    protected $executionAdapter;

    /**
     * @var \Spryker\Zed\Payone\Persistence\PayoneQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Generated\Shared\Transfer\PayoneStandardParameterTransfer
     */
    protected $standardParameter;

    /**
     * @var \Spryker\Zed\Payone\Business\SequenceNumber\SequenceNumberProviderInterface
     */
    protected $sequenceNumberProvider;

    /**
     * @var \Spryker\Shared\Payone\Dependency\ModeDetectorInterface
     */
    protected $modeDetector;

    /**
     * @var \Spryker\Zed\Payone\Business\Payment\PaymentMethodMapperInterface[]
     */
    protected $registeredMethodMappers;

    /**
     * @param \Spryker\Zed\Payone\Business\Api\Adapter\AdapterInterface $executionAdapter
     * @param \Spryker\Zed\Payone\Persistence\PayoneQueryContainerInterface $queryContainer
     * @param \Generated\Shared\Transfer\PayoneStandardParameterTransfer $standardParameter
     * @param \Spryker\Zed\Payone\Business\Key\HashGenerator $hashGenerator
     * @param \Spryker\Zed\Payone\Business\SequenceNumber\SequenceNumberProviderInterface $sequenceNumberProvider
     * @param \Spryker\Shared\Payone\Dependency\ModeDetectorInterface $modeDetector
     */
    public function __construct(
        AdapterInterface $executionAdapter,
        PayoneQueryContainerInterface $queryContainer,
        PayoneStandardParameterTransfer $standardParameter,
        HashGenerator $hashGenerator,
        SequenceNumberProviderInterface $sequenceNumberProvider,
        ModeDetectorInterface $modeDetector
    ) {

        $this->executionAdapter = $executionAdapter;
        $this->queryContainer = $queryContainer;
        $this->standardParameter = $standardParameter;
        $this->hashGenerator = $hashGenerator;
        $this->sequenceNumberProvider = $sequenceNumberProvider;
        $this->modeDetector = $modeDetector;
    }

    /**
     * @param \Spryker\Zed\Payone\Business\Payment\PaymentMethodMapperInterface $paymentMethodMapper
     *
     * @return void
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
     * @return \Spryker\Zed\Payone\Business\Payment\PaymentMethodMapperInterface|null
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
     * @throws \Spryker\Zed\Payone\Business\Exception\InvalidPaymentMethodException
     *
     * @return \Spryker\Zed\Payone\Business\Payment\PaymentMethodMapperInterface
     */
    protected function getRegisteredPaymentMethodMapper($paymentMethodName)
    {
        $paymentMethodMapper = $this->findPaymentMethodMapperByName($paymentMethodName);
        if ($paymentMethodMapper === null) {
            throw new InvalidPaymentMethodException(
                sprintf('No registered payment method mapper found for given method name %s', $paymentMethodName)
            );
        }

        return $paymentMethodMapper;
    }

    /**
     * @param int $idPayment
     *
     * @return \Spryker\Zed\Payone\Business\Api\Response\Container\AuthorizationResponseContainer
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
     * @return \Spryker\Zed\Payone\Business\Api\Response\Container\AuthorizationResponseContainer
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
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentEntity
     * @param \Spryker\Zed\Payone\Business\Api\Request\Container\AuthorizationContainerInterface $requestContainer
     *
     * @return \Spryker\Zed\Payone\Business\Api\Response\Container\AuthorizationResponseContainer
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
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentEntity
     *
     * @return \Spryker\Zed\Payone\Business\Payment\PaymentMethodMapperInterface
     */
    protected function getPaymentMethodMapper(SpyPaymentPayone $paymentEntity)
    {
        return $this->getRegisteredPaymentMethodMapper($paymentEntity->getPaymentMethod());
    }

    /**
     * @param int $idPayment
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayone
     */
    protected function getPaymentEntity($idPayment)
    {
        return $this->queryContainer->getPaymentById($idPayment)->findOne();
    }

    /**
     * @param int $idPayment
     *
     * @return \Spryker\Zed\Payone\Business\Api\Response\Container\CaptureResponseContainer
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
     * @return \Spryker\Zed\Payone\Business\Api\Response\Container\DebitResponseContainer
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
     * @param \Generated\Shared\Transfer\PayoneCreditCardTransfer $creditCardData
     *
     * @return \Spryker\Zed\Payone\Business\Api\Response\Container\CreditCardCheckResponseContainer
     */
    public function creditCardCheck(PayoneCreditCardTransfer $creditCardData)
    {
        /** @var \Spryker\Zed\Payone\Business\Payment\MethodMapper\CreditCardPseudo $paymentMethodMapper */
        $paymentMethodMapper = $this->getRegisteredPaymentMethodMapper($creditCardData->getPayment()->getPaymentMethod());
        $requestContainer = $paymentMethodMapper->mapCreditCardCheck($creditCardData);
        $this->setStandardParameter($requestContainer);

        $rawResponse = $this->executionAdapter->sendRequest($requestContainer);
        $responseContainer = new CreditCardCheckResponseContainer($rawResponse);

        return $responseContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\PayoneRefundTransfer $refundTransfer
     *
     * @return \Spryker\Zed\Payone\Business\Api\Response\Container\RefundResponseContainer
     */
    public function refundPayment(PayoneRefundTransfer $refundTransfer)
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
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\PayonePaymentTransfer
     */
    protected function getPayment(OrderTransfer $orderTransfer)
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
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentEntity
     * @param \Spryker\Zed\Payone\Business\Api\Response\Container\AuthorizationResponseContainer $responseContainer
     *
     * @return void
     */
    protected function updatePaymentAfterAuthorization(SpyPaymentPayone $paymentEntity, AuthorizationResponseContainer $responseContainer)
    {
        $paymentEntity->setTransactionId($responseContainer->getTxid());
        $paymentEntity->save();
    }

    /**
     * @param string $transactionId
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayone
     */
    protected function findPaymentByTransactionId($transactionId)
    {
        return $this->queryContainer->getPaymentByTransactionIdQuery($transactionId)->findOne();
    }

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentEntity
     * @param \Spryker\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer $container
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneApiLog
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
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayoneApiLog $apiLogEntity
     * @param \Spryker\Zed\Payone\Business\Api\Response\Container\AuthorizationResponseContainer $responseContainer
     *
     * @return void
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
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayoneApiLog $apiLogEntity
     * @param \Spryker\Zed\Payone\Business\Api\Response\Container\CaptureResponseContainer $responseContainer
     *
     * @return void
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
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayoneApiLog $apiLogEntity
     * @param \Spryker\Zed\Payone\Business\Api\Response\Container\DebitResponseContainer $responseContainer
     *
     * @return void
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
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayoneApiLog $apiLogEntity
     * @param \Spryker\Zed\Payone\Business\Api\Response\Container\RefundResponseContainer $responseContainer
     *
     * @return void
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
     * @param \Spryker\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer $container
     *
     * @return void
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
     * @return \Generated\Shared\Transfer\PaymentDataTransfer
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
     * @param \Propel\Runtime\Collection\ObjectCollection $orders
     *
     * @return \Generated\Shared\Transfer\PayonePaymentLogTransfer[]
     */
    public function getPaymentLogs(ObjectCollection $orders)
    {
        $apiLogs = $this->queryContainer->getApiLogsByOrderIds($orders)->find()->getData();

        $transactionStatusLogs = $this->queryContainer->getTransactionStatusLogsByOrderIds($orders)->find()->getData();

        $logs = [];
        /** @var \Orm\Zed\Payone\Persistence\SpyPaymentPayoneApiLog $apiLog */
        foreach ($apiLogs as $apiLog) {
            $key = $apiLog->getCreatedAt()->format('Y-m-d\TH:i:s\Z') . 'a' . $apiLog->getIdPaymentPayoneApiLog();
            $payonePaymentLogTransfer = new PayonePaymentLogTransfer();
            $payonePaymentLogTransfer->fromArray($apiLog->toArray(), true);
            $payonePaymentLogTransfer->setLogType(self::LOG_TYPE_API_LOG);
            $logs[$key] = $payonePaymentLogTransfer;
        }
        /** @var \Orm\Zed\Payone\Persistence\SpyPaymentPayoneTransactionStatusLog $transactionStatusLog */
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
     * @param \Generated\Shared\Transfer\PayoneCreditCardCheckRequestDataTransfer $creditCardCheckRequestDataTransfer
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
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isRefundPossible(OrderTransfer $orderTransfer)
    {
        $paymentTransfer = $this->getPayment($orderTransfer);

        if (!$this->isPaymentDataRequired($orderTransfer)) {
            return true;
        }

        $paymentDetailTransfer = $paymentTransfer->getPaymentDetail();

        return $paymentDetailTransfer->getBic() && $paymentDetailTransfer->getIban();
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isPaymentDataRequired(OrderTransfer $orderTransfer)
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
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function postSaveHook(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse)
    {
        $apiLogsQuery = $this->queryContainer->getLastApiLogsByOrderId($quoteTransfer->getIdSalesOrder());
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
     * @param \Generated\Shared\Transfer\PaymentDataTransfer $paymentDataTransfer
     * @param int $idOrder
     *
     * @return void
     */
    public function updatePaymentDetail(PaymentDataTransfer $paymentDataTransfer, $idOrder)
    {
        $paymentEntity = $this->queryContainer->getPaymentByOrderId($idOrder)->findOne();
        $paymentDetailEntity = $paymentEntity->getSpyPaymentPayoneDetail();

        $paymentDetailEntity->fromArray($paymentDataTransfer->toArray());

        $paymentDetailEntity->save();
    }

}
