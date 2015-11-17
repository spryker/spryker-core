<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business\Payment\Handler\Transaction;

use SprykerFeature\Zed\Payolution\Business\Api\Adapter\AdapterInterface;
use SprykerFeature\Zed\Payolution\Business\Api\Converter\ConverterInterface;
use SprykerFeature\Zed\Payolution\Business\Payment\Handler\AbstractPaymentHandler;
use SprykerFeature\Zed\Payolution\Business\Payment\Method\ApiConstants;
use SprykerFeature\Zed\Payolution\Persistence\PayolutionQueryContainerInterface;
use Orm\Zed\Payolution\Persistence\SpyPaymentPayolution;
use Orm\Zed\Payolution\Persistence\SpyPaymentPayolutionTransactionRequestLog;
use Orm\Zed\Payolution\Persistence\SpyPaymentPayolutionTransactionStatusLog;
use SprykerFeature\Zed\Payolution\PayolutionConfig;
use Generated\Shared\Payolution\PayolutionTransactionResponseInterface;
use Generated\Shared\Payolution\CheckoutRequestInterface;

class Transaction extends AbstractPaymentHandler implements TransactionInterface
{

    /**
     * @var PayolutionQueryContainerInterface
     */
    private $queryContainer;

    /**
     * @param AdapterInterface $executionAdapter
     * @param ConverterInterface $converter
     * @param PayolutionQueryContainerInterface $queryContainer
     * @param PayolutionConfig $config
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
     * @param CheckoutRequestInterface $checkoutRequestTransfer
     *
     * @return PayolutionTransactionResponseInterface
     */
    public function preCheckPayment(CheckoutRequestInterface $checkoutRequestTransfer)
    {
        $paymentTransfer = $checkoutRequestTransfer->getPayolutionPayment();
        $requestData = $this
            ->getMethodMapper($paymentTransfer->getAccountBrand())
            ->buildPreCheckRequest($checkoutRequestTransfer);

        return $this->sendRequest($requestData);
    }

    /**
     * @param int $idPayment
     *
     * @return PayolutionTransactionResponseInterface
     */
    public function preAuthorizePayment($idPayment)
    {
        $paymentEntity = $this->getPaymentEntity($idPayment);
        $methodMapper = $this->getMethodMapper($paymentEntity->getAccountBrand());

        $this->checkMaxMinGrandTotal(
            $paymentEntity->getSpySalesOrder()->getGrandTotal(),
            $methodMapper->getMinGrandTotal(),
            $methodMapper->getMaxGrandTotal()
        );

        $requestData = $methodMapper->buildPreAuthorizationRequest($paymentEntity);

        return $this->sendLoggedRequest($requestData, $paymentEntity);
    }

    /**
     * @param int $idPayment
     *
     * @return PayolutionTransactionResponseInterface
     */
    public function reAuthorizePayment($idPayment)
    {
        $paymentEntity = $this->getPaymentEntity($idPayment);
        $statusLogEntity = $this->getLatestTransactionStatusLogItem($idPayment);

        $requestData = $this
            ->getMethodMapper($paymentEntity->getAccountBrand())
            ->buildReAuthorizationRequest($paymentEntity, $statusLogEntity->getIdentificationUniqueid());

        return $this->sendLoggedRequest($requestData, $paymentEntity);
    }

    /**
     * @param int $idPayment
     *
     * @return PayolutionTransactionResponseInterface
     */
    public function revertPayment($idPayment)
    {
        $paymentEntity = $this->getPaymentEntity($idPayment);
        $statusLogEntity = $this->getLatestTransactionStatusLogItem($idPayment);

        $requestData = $this
            ->getMethodMapper($paymentEntity->getAccountBrand())
            ->buildRevertRequest($paymentEntity, $statusLogEntity->getIdentificationUniqueid());

        return $this->sendLoggedRequest($requestData, $paymentEntity);
    }

    /**
     * @param int $idPayment
     *
     * @return PayolutionTransactionResponseInterface
     */
    public function capturePayment($idPayment)
    {
        $paymentEntity = $this->getPaymentEntity($idPayment);
        $statusLogEntity = $this->getLatestTransactionStatusLogItem($idPayment);

        $requestData = $this
            ->getMethodMapper($paymentEntity->getAccountBrand())
            ->buildCaptureRequest($paymentEntity, $statusLogEntity->getIdentificationUniqueid());

        return $this->sendLoggedRequest($requestData, $paymentEntity);
    }

    /**
     * @param int $idPayment
     *
     * @return PayolutionTransactionResponseInterface
     */
    public function refundPayment($idPayment)
    {
        $paymentEntity = $this->getPaymentEntity($idPayment);
        $statusLogEntity = $this->getLatestTransactionStatusLogItem($idPayment);

        $requestData = $this
            ->getMethodMapper($paymentEntity->getAccountBrand())
            ->buildRefundRequest($paymentEntity, $statusLogEntity->getIdentificationReferenceid());

        return $this->sendLoggedRequest($requestData, $paymentEntity);
    }

    /**
     * @param int $idPayment
     *
     * @return SpyPaymentPayolution
     */
    protected function getPaymentEntity($idPayment)
    {
        return $this->queryContainer->queryPaymentById($idPayment)->findOne();
    }

    /**
     * @param int $idPayment
     *
     * @return SpyPaymentPayolutionTransactionStatusLog
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
     * @param SpyPaymentPayolution $paymentEntity
     *
     * @return PayolutionTransactionResponseInterface
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
     * @return PayolutionTransactionResponseInterface
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
     * @return SpyPaymentPayolutionTransactionRequestLog
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
     * @param PayolutionTransactionResponseInterface $responseTransfer
     * @param int $idPayment
     */
    protected function logApiResponse(PayolutionTransactionResponseInterface $responseTransfer, $idPayment)
    {
        $logEntity = new SpyPaymentPayolutionTransactionStatusLog();
        $logEntity->fromArray($responseTransfer->toArray());
        $logEntity->setFkPaymentPayolution($idPayment);
        $logEntity->save();
    }

}
