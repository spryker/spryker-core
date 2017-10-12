<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payolution\Business\Payment\Handler\Transaction;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PayolutionTransactionResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Payolution\Persistence\SpyPaymentPayolution;
use Orm\Zed\Payolution\Persistence\SpyPaymentPayolutionTransactionRequestLog;
use Orm\Zed\Payolution\Persistence\SpyPaymentPayolutionTransactionStatusLog;
use Spryker\Zed\Payolution\Business\Api\Adapter\AdapterInterface;
use Spryker\Zed\Payolution\Business\Api\Converter\ConverterInterface;
use Spryker\Zed\Payolution\Business\Payment\Handler\AbstractPaymentHandler;
use Spryker\Zed\Payolution\Business\Payment\Method\ApiConstants;
use Spryker\Zed\Payolution\PayolutionConfig;
use Spryker\Zed\Payolution\Persistence\PayolutionQueryContainerInterface;

class Transaction extends AbstractPaymentHandler implements TransactionInterface
{
    /**
     * @var \Spryker\Zed\Payolution\Persistence\PayolutionQueryContainerInterface
     */
    private $queryContainer;

    /**
     * @param \Spryker\Zed\Payolution\Business\Api\Adapter\AdapterInterface $executionAdapter
     * @param \Spryker\Zed\Payolution\Business\Api\Converter\ConverterInterface $converter
     * @param \Spryker\Zed\Payolution\Persistence\PayolutionQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Payolution\PayolutionConfig $config
     */
    public function __construct(
        AdapterInterface $executionAdapter,
        ConverterInterface $converter,
        PayolutionQueryContainerInterface $queryContainer,
        PayolutionConfig $config
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
     * @return \Generated\Shared\Transfer\PayolutionTransactionResponseTransfer
     */
    public function preCheckPayment(QuoteTransfer $quoteTransfer)
    {
        $paymentTransfer = $quoteTransfer->getPayment()->getPayolution();
        $requestData = $this
            ->getMethodMapper($paymentTransfer->getAccountBrand())
            ->buildPreCheckRequest($quoteTransfer);

        return $this->sendRequest($requestData);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param int $idPayment
     *
     * @return \Generated\Shared\Transfer\PayolutionTransactionResponseTransfer
     */
    public function preAuthorizePayment(OrderTransfer $orderTransfer, $idPayment)
    {
        $paymentEntity = $this->getPaymentEntity($idPayment);
        $methodMapper = $this->getMethodMapper($paymentEntity->getAccountBrand());

        $this->checkMaxMinGrandTotal(
            $orderTransfer->getTotals()->getGrandTotal(),
            $methodMapper->getMinGrandTotal(),
            $methodMapper->getMaxGrandTotal()
        );

        $requestData = $methodMapper->buildPreAuthorizationRequest($orderTransfer, $paymentEntity);

        return $this->sendLoggedRequest($requestData, $paymentEntity);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param int $idPayment
     *
     * @return \Generated\Shared\Transfer\PayolutionTransactionResponseTransfer
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
     * @return \Generated\Shared\Transfer\PayolutionTransactionResponseTransfer
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
     * @return \Generated\Shared\Transfer\PayolutionTransactionResponseTransfer
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
     * @return \Generated\Shared\Transfer\PayolutionTransactionResponseTransfer
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
     * @return \Orm\Zed\Payolution\Persistence\SpyPaymentPayolution
     */
    protected function getPaymentEntity($idPayment)
    {
        return $this->queryContainer->queryPaymentById($idPayment)->findOne();
    }

    /**
     * @param int $idPayment
     *
     * @return \Orm\Zed\Payolution\Persistence\SpyPaymentPayolutionTransactionStatusLog
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
     * @param \Orm\Zed\Payolution\Persistence\SpyPaymentPayolution $paymentEntity
     *
     * @return \Generated\Shared\Transfer\PayolutionTransactionResponseTransfer
     */
    protected function sendLoggedRequest(array $requestData, SpyPaymentPayolution $paymentEntity)
    {
        $this->logApiRequest($requestData, $paymentEntity->getIdPaymentPayolution());
        $responseTransfer = $this->sendRequest($requestData);
        $this->logApiResponse($responseTransfer, $paymentEntity->getIdPaymentPayolution());

        return $responseTransfer;
    }

    /**
     * @param array $transactionRequest
     *
     * @return \Generated\Shared\Transfer\PayolutionTransactionResponseTransfer
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
     * @return \Orm\Zed\Payolution\Persistence\SpyPaymentPayolutionTransactionRequestLog
     */
    protected function logApiRequest($requestData, $idPayment)
    {
        $logEntity = new SpyPaymentPayolutionTransactionRequestLog();
        $logEntity
            ->setPaymentCode($requestData[ApiConstants::PAYMENT_CODE])
            ->setPresentationAmount($requestData[ApiConstants::PRESENTATION_AMOUNT])
            ->setPresentationCurrency($requestData[ApiConstants::PRESENTATION_CURRENCY])
            ->setTransactionId($requestData[ApiConstants::IDENTIFICATION_TRANSACTIONID])
            ->setReferenceId($requestData[ApiConstants::IDENTIFICATION_REFERENCEID])
            ->setFkPaymentPayolution($idPayment);
        $logEntity->save();

        return $logEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\PayolutionTransactionResponseTransfer $responseTransfer
     * @param int $idPayment
     *
     * @return void
     */
    protected function logApiResponse(PayolutionTransactionResponseTransfer $responseTransfer, $idPayment)
    {
        $logEntity = new SpyPaymentPayolutionTransactionStatusLog();
        $logEntity->fromArray($responseTransfer->toArray());
        $logEntity->setFkPaymentPayolution($idPayment);
        $logEntity->save();
    }
}
