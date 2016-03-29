<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Braintree\Business\Payment\Handler\Transaction;

use Generated\Shared\Transfer\BraintreeTransactionResponseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Braintree\Persistence\SpyPaymentBraintree;
use Orm\Zed\Braintree\Persistence\SpyPaymentBraintreeTransactionRequestLog;
use Orm\Zed\Braintree\Persistence\SpyPaymentBraintreeTransactionStatusLog;
use Spryker\Zed\Braintree\BraintreeConfig;
use Spryker\Zed\Payolution\Business\Api\Adapter\AdapterInterface;
use Spryker\Zed\Payolution\Business\Api\Converter\ConverterInterface;
use Spryker\Zed\Braintree\Business\Payment\Handler\AbstractPaymentHandler;
use Spryker\Zed\Braintree\Business\Payment\Method\ApiConstants;
use Spryker\Zed\Braintree\Persistence\BraintreeQueryContainerInterface;

class Transaction extends AbstractPaymentHandler implements TransactionInterface
{

    /**
     * @var \Spryker\Zed\Braintree\Persistence\BraintreeQueryContainerInterface
     */
    private $queryContainer;

    /**
     * @param \Spryker\Zed\Payolution\Business\Api\Adapter\AdapterInterface $executionAdapter
     * @param \Spryker\Zed\Payolution\Business\Api\Converter\ConverterInterface $converter
     * @param \Spryker\Zed\Braintree\Persistence\BraintreeQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Braintree\BraintreeConfig $config
     */
    public function __construct(
        AdapterInterface $executionAdapter,
        ConverterInterface $converter,
        BraintreeQueryContainerInterface $queryContainer,
        BraintreeConfig $config
    ) {
        parent::__construct(
            $executionAdapter,
            $converter,
            $config
        );

        $this->queryContainer = $queryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\BraintreeTransactionResponseTransfer
     */
    public function preCheckPayment(QuoteTransfer $quoteTransfer)
    {
        $paymentTransfer = $quoteTransfer->getPayment()->getBraintree();
        $requestData = $this
            ->getMethodMapper($paymentTransfer->getAccountBrand())
            ->buildPreCheckRequest($quoteTransfer);

        return $this->sendRequest($requestData);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param int $idPayment
     *
     * @return \Generated\Shared\Transfer\BraintreeTransactionResponseTransfer
     */
    public function preAuthorizePayment(OrderTransfer $orderTransfer, $idPayment)
    {
        $paymentEntity = $this->getPaymentEntity($idPayment);
        $methodMapper = $this->getMethodMapper($paymentEntity->getAccountBrand());

        $requestData = $methodMapper->buildPreAuthorizationRequest($orderTransfer, $paymentEntity);

        return $this->sendLoggedRequest($requestData, $paymentEntity);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param int $idPayment
     *
     * @return \Generated\Shared\Transfer\BraintreeTransactionResponseTransfer
     */
    public function reAuthorizePayment(OrderTransfer $orderTransfer, $idPayment)
    {
        $paymentEntity = $this->getPaymentEntity($idPayment);
        $statusLogEntity = $this->getLatestTransactionStatusLogItem($idPayment);

        $requestData = $this
            ->getMethodMapper($paymentEntity->getAccountBrand())
            ->buildReAuthorizationRequest($orderTransfer, $paymentEntity, $statusLogEntity->getIdentificationUniqueid());

        return $this->sendLoggedRequest($requestData, $paymentEntity);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param int $idPayment
     *
     * @return \Generated\Shared\Transfer\BraintreeTransactionResponseTransfer
     */
    public function revertPayment(OrderTransfer $orderTransfer, $idPayment)
    {
        $paymentEntity = $this->getPaymentEntity($idPayment);
        $statusLogEntity = $this->getLatestTransactionStatusLogItem($idPayment);

        $requestData = $this
            ->getMethodMapper($paymentEntity->getAccountBrand())
            ->buildRevertRequest($orderTransfer, $paymentEntity, $statusLogEntity->getIdentificationUniqueid());

        return $this->sendLoggedRequest($requestData, $paymentEntity);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param int $idPayment
     *
     * @return \Generated\Shared\Transfer\BraintreeTransactionResponseTransfer
     */
    public function capturePayment(OrderTransfer $orderTransfer, $idPayment)
    {
        $paymentEntity = $this->getPaymentEntity($idPayment);
        $statusLogEntity = $this->getLatestTransactionStatusLogItem($idPayment);

        $requestData = $this
            ->getMethodMapper($paymentEntity->getAccountBrand())
            ->buildCaptureRequest($orderTransfer, $paymentEntity, $statusLogEntity->getIdentificationUniqueid());

        return $this->sendLoggedRequest($requestData, $paymentEntity);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param int $idPayment
     *
     * @return \Generated\Shared\Transfer\BraintreeTransactionResponseTransfer
     */
    public function refundPayment(OrderTransfer $orderTransfer, $idPayment)
    {
        $paymentEntity = $this->getPaymentEntity($idPayment);
        $statusLogEntity = $this->getLatestTransactionStatusLogItem($idPayment);

        $requestData = $this
            ->getMethodMapper($paymentEntity->getAccountBrand())
            ->buildRefundRequest($orderTransfer, $paymentEntity, $statusLogEntity->getIdentificationUniqueid());

        return $this->sendLoggedRequest($requestData, $paymentEntity);
    }

    /**
     * @param int $idPayment
     *
     * @return \Orm\Zed\Braintree\Persistence\SpyPaymentBraintree
     */
    protected function getPaymentEntity($idPayment)
    {
        return $this->queryContainer->queryPaymentById($idPayment)->findOne();
    }

    /**
     * @param int $idPayment
     *
     * @return \Orm\Zed\Braintree\Persistence\SpyPaymentBraintreeTransactionStatusLog
     */
    protected function getLatestTransactionStatusLogItem($idPayment)
    {
        return $this
            ->queryContainer
            ->queryTransactionStatusLogByPaymentIdLatestFirst($idPayment)
            ->findOne();
    }

    /**
     * @param array $requestData
     * @param \Orm\Zed\Braintree\Persistence\SpyPaymentBraintree $paymentEntity
     *
     * @return \Generated\Shared\Transfer\BraintreeTransactionResponseTransfer
     */
    protected function sendLoggedRequest(array $requestData, SpyPaymentBraintree $paymentEntity)
    {
        $this->logApiRequest($requestData, $paymentEntity->getIdPaymentBraintree());
        $responseTransfer = $this->sendRequest($requestData);
        $this->logApiResponse($responseTransfer, $paymentEntity->getIdPaymentBraintree());

        return $responseTransfer;
    }

    /**
     * @param array $transactionRequest
     *
     * @return \Generated\Shared\Transfer\BraintreeTransactionResponseTransfer
     */
    protected function sendRequest($transactionRequest)
    {
        $responseData = $this->executionAdapter->sendRequest($transactionRequest);
        $responseTransfer = $this->converter->toTransactionResponseTransfer($responseData);

        return $responseTransfer;
    }

    /**
     * @param array $requestData
     * @param int $idPayment
     *
     * @return \Orm\Zed\Braintree\Persistence\SpyPaymentBraintreeTransactionRequestLog
     */
    protected function logApiRequest($requestData, $idPayment)
    {
        $logEntity = new SpyPaymentBraintreeTransactionRequestLog();
        $logEntity
            ->setPaymentCode($requestData[ApiConstants::PAYMENT_CODE])
            ->setPresentationAmount($requestData[ApiConstants::PRESENTATION_AMOUNT])
            ->setPresentationCurrency($requestData[ApiConstants::PRESENTATION_CURRENCY])
            ->setTransactionId($requestData[ApiConstants::IDENTIFICATION_TRANSACTIONID])
            ->setReferenceId($requestData[ApiConstants::IDENTIFICATION_REFERENCEID])
            ->setFkPaymentBraintree($idPayment);
        $logEntity->save();

        return $logEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\BraintreeTransactionResponseTransfer $responseTransfer
     * @param int $idPayment
     *
     * @return void
     */
    protected function logApiResponse(BraintreeTransactionResponseTransfer $responseTransfer, $idPayment)
    {
        $logEntity = new SpyPaymentBraintreeTransactionStatusLog();
        $logEntity->fromArray($responseTransfer->toArray());
        $logEntity->setFkPaymentBraintree($idPayment);
        $logEntity->save();
    }

}
