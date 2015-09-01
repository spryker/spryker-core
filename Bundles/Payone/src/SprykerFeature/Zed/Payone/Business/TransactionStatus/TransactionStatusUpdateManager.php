<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payone\Business\TransactionStatus;

use Generated\Shared\Payone\PayoneStandardParameterInterface;
use SprykerFeature\Shared\Payone\Dependency\HashInterface;
use SprykerFeature\Shared\Payone\Dependency\TransactionStatusUpdateInterface;
use SprykerFeature\Shared\Payone\PayoneTransactionStatusConstants;
use SprykerFeature\Zed\Payone\Business\Api\TransactionStatus\TransactionStatusRequest;
use SprykerFeature\Zed\Payone\Business\Api\TransactionStatus\TransactionStatusResponse;
use SprykerFeature\Zed\Payone\Persistence\PayoneQueryContainerInterface;
use SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayone;
use SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayoneTransactionStatusLog;
use SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayoneTransactionStatusLogOrderItem;

class TransactionStatusUpdateManager
{

    /**
     * @var PayoneQueryContainerInterface
     */
    protected $queryContainer;
    /**
     * @var PayoneStandardParameterInterface
     */
    protected $standardParameter;
    /**
     * @var HashInterface
     */
    protected $hashProvider;

    /**
     * @param PayoneQueryContainerInterface $queryContainer
     * @param PayoneStandardParameterInterface $standardParameter
     * @param HashInterface $hashProvider
     */
    public function __construct(
        PayoneQueryContainerInterface $queryContainer,
        PayoneStandardParameterInterface $standardParameter,
        HashInterface $hashProvider)
    {
        $this->queryContainer = $queryContainer;
        $this->standardParameter = $standardParameter;
        $this->hashProvider = $hashProvider;
    }

    /**
     * @param TransactionStatusUpdateInterface $request
     *
     * @return TransactionStatusResponse
     */
    public function processTransactionStatusUpdate(TransactionStatusUpdateInterface $request)
    {
        $validationResult = $this->validate($request);
        if ($validationResult instanceof TransactionStatusResponse) {
            return $validationResult;
        }
        $this->transformCurrency($request);
        $this->persistRequest($request);

        return $this->createSuccessResponse();
    }

    /**
     * @param TransactionStatusUpdateInterface $request
     */
    protected function persistRequest(TransactionStatusUpdateInterface $request)
    {
        $entity = new SpyPaymentPayoneTransactionStatusLog();

        $entity->setSpyPaymentPayone($this->findPaymentByTransactionId($request->getTxid()));
        $entity->setTransactionId($request->getTxid());
        $entity->setReferenceId($request->getReference());
        $entity->setMode($request->getMode());
        $entity->setStatus($request->getTxaction());
        $entity->setTransactionTime($request->getTxtime());
        $entity->setSequenceNumber($request->getSequencenumber());
        $entity->setClearingType($request->getClearingtype());
        $entity->setPortalId($request->getPortalid());
        $entity->setPrice($request->getPrice());
        $entity->setBalance($request->getBalance());
        $entity->setReceivable($request->getReceivable());
        $entity->setReminderlevel($request->getReminderlevel());

        $entity->save();
    }

    public function getPaymentStatus($order)
    {
//        @todo implement
//        $order->getTransactionId();
//        $this->findPaymentByTransactionId();
//        $paymentStatus = new PaymentStatusTransfer();
//        $paymentStatus->setIsSuccess();
//        $paymentStatus->setRedirectUrl();
//        return $transactionStatus;
    }

    /**
     * @param int $idSalesOrder
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    public function isPaymentNotificationAvailable($idSalesOrder, $idSalesOrderItem)
    {
        return $this->hasUnprocessedTransactionStatusLogs($idSalesOrder, $idSalesOrderItem);
    }

    /**
     * @param TransactionStatusUpdateInterface $request
     */
    protected function transformCurrency(TransactionStatusUpdateInterface $request)
    {
        $balance = $request->getBalance();
        $newBalance = (int) (round($balance * 100));
        $request->setBalance($newBalance);

        $receivable = $request->getReceivable();
        $newReceivable = (int) (round($receivable * 100));
        $request->setReceivable($newReceivable);
    }

    /**
     * @param TransactionStatusRequest $request
     *
     * @return bool|TransactionStatusResponse
     */
    protected function validate(TransactionStatusUpdateInterface $request)
    {
        $systemHashedKey = $this->hashProvider->hash($this->standardParameter->getKey());
        if ($request->getKey() !== $systemHashedKey) {
            return $this->createErrorResponse('Payone transaction status update: Given and internal key do not match!');
        }

        if ($request->getAid() !== $this->standardParameter->getAid()) {
            return $this->createErrorResponse('Payone transaction status update: Invalid Aid! System: ' . $this->standardParameter->getAid() . ' Request: ' . $request->getAid());
        }

        if ($request->getPortalid() !== $this->standardParameter->getPortalId()) {
            return $this->createErrorResponse('Payone transaction status update: Invalid Portalid! System: ' . $this->standardParameter->getPortalId() . ' Request: ' . $request->getPortalid());
        }

        return true;
    }

    /**
     * @param string $errorMessage
     *
     * @return TransactionStatusResponse
     */
    protected function createErrorResponse($errorMessage)
    {
        $response = new TransactionStatusResponse(false);
        $response->setErrorMessage($errorMessage);

        return $response;
    }

    /**
     * @return TransactionStatusResponse
     */
    protected function createSuccessResponse()
    {
        $response = new TransactionStatusResponse(true);

        return $response;
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

    public function isPaymentPaid($idSalesOrder, $idSalesOrderItem)
    {
        $status = PayoneTransactionStatusConstants::TXACTION_PAID;
        $statusLog = $this->getFirstUnprocessedTransactionStatusLog($idSalesOrder, $idSalesOrderItem, $status);
        if ($statusLog === null) {
            return false;
        }
        if ($statusLog->getBalance() !== 0) {
            return false;
        }

        $this->saveSpyPaymentPayoneTransactionStatusLogOrderItem($idSalesOrderItem, $statusLog);

        return true;
    }

    public function isPaymentCapture($idSalesOrder, $idSalesOrderItem)
    {
        $status = PayoneTransactionStatusConstants::TXACTION_CAPTURE;
        return $this->isPayment($idSalesOrder, $idSalesOrderItem, $status);
    }

    public function isPaymentOverpaid($idSalesOrder, $idSalesOrderItem)
    {
        $status = PayoneTransactionStatusConstants::TXACTION_PAID;
        $statusLog = $this->getFirstUnprocessedTransactionStatusLog($idSalesOrder, $idSalesOrderItem, $status);
        if ($statusLog === null) {
            return false;
        }
        if ($statusLog->getBalance() >= 0) {
            return false;
        }

        $this->saveSpyPaymentPayoneTransactionStatusLogOrderItem($idSalesOrderItem, $statusLog);

        return true;
    }

    public function isPaymentUnderpaid($idSalesOrder, $idSalesOrderItem)
    {
        $status = PayoneTransactionStatusConstants::TXACTION_UNDERPAID;
        return $this->isPayment($idSalesOrder, $idSalesOrderItem, $status);
    }

    public function isPaymentAppointed($idSalesOrder, $idSalesOrderItem)
    {
        $status = PayoneTransactionStatusConstants::TXACTION_APPOINTED;
        return $this->isPayment($idSalesOrder, $idSalesOrderItem, $status);
    }

    public function isPaymentOther($idSalesOrder, $idSalesOrderItem)
    {
        $statusLogs = $this->getUnprocessedTransactionStatusLogs($idSalesOrder, $idSalesOrderItem);
        if (empty($statusLogs)) {
            return false;
        }

        /** @var SpyPaymentPayoneTransactionStatusLog $statusLog */
        $statusLog = array_shift($statusLogs);

        $statuses = [
            PayoneTransactionStatusConstants::TXACTION_PAID,
            PayoneTransactionStatusConstants::TXACTION_APPOINTED,
            PayoneTransactionStatusConstants::TXACTION_UNDERPAID,
        ];
        if (in_array($statusLog->getStatus(), $statuses)) {
            return false;
        }

        $this->saveSpyPaymentPayoneTransactionStatusLogOrderItem($idSalesOrderItem, $statusLog);

        return true;
    }

    /**
     * @param $idSalesOrder
     * @param $idSalesOrderItem
     * @param $status
     *
     * @return bool
     */
    private function isPayment($idSalesOrder, $idSalesOrderItem, $status)
    {
        $statusLog = $this->getFirstUnprocessedTransactionStatusLog($idSalesOrder, $idSalesOrderItem, $status);
        if ($statusLog === null) {
            return false;
        }

        $this->saveSpyPaymentPayoneTransactionStatusLogOrderItem($idSalesOrderItem, $statusLog);

        return true;
    }

    /**
     * @param $idSalesOrder
     * @param $idSalesOrderItem
     *
     * @return bool
     */
    private function hasUnprocessedTransactionStatusLogs($idSalesOrder, $idSalesOrderItem) {
        $records = $this->getUnprocessedTransactionStatusLogs($idSalesOrder, $idSalesOrderItem);

        return !empty($records);
    }

    /**
     * @param $idSalesOrder
     * @param $idSalesOrderItem
     * @param $status
     *
     * @return SpyPaymentPayoneTransactionStatusLog
     */
    private function getFirstUnprocessedTransactionStatusLog($idSalesOrder, $idSalesOrderItem, $status) {
        $records = $this->getUnprocessedTransactionStatusLogs($idSalesOrder, $idSalesOrderItem);

        if (empty($records)) {
            return;
        }

        /** @var SpyPaymentPayoneTransactionStatusLog $record */
        $record = array_shift($records);

        if ($record->getStatus() !== $status) {
            return;
        }

        return $record;
    }

    /**
     * @param $idSalesOrder
     * @param $idSalesOrderItem
     *
     * @return SpyPaymentPayoneTransactionStatusLog[]
     */
    private function getUnprocessedTransactionStatusLogs($idSalesOrder, $idSalesOrderItem) {
        $transactionStatusLogs = $this->queryContainer->getTransactionStatusLogsBySalesOrder($idSalesOrder)->find();

        $ids = [];

        /* @var SpyPaymentPayoneTransactionStatusLog $record */
        foreach ($transactionStatusLogs as $transactionStatusLog) {
            $ids[$record->getIdPaymentPayoneTransactionStatusLog()] = $transactionStatusLog;
        }

        $relations = $this->queryContainer
            ->getTransactionStatusLogOrderItemsByLogIds($idSalesOrderItem, array_keys($ids))
            ->find()
            ;

        /** @var SpyPaymentPayoneTransactionStatusLogOrderItem $relation */
        foreach ($relations as $relation) {
            unset($ids[$relation->getIdPaymentPayoneTransactionStatusLog()]);
        }

        return $ids;
    }

    /**
     * @param int $idSalesOrderItem
     * @param SpyPaymentPayoneTransactionStatusLog $statusLog
     *
     * @throws \Propel\Runtime\Exception\PropelException
     */
    private function saveSpyPaymentPayoneTransactionStatusLogOrderItem($idSalesOrderItem, SpyPaymentPayoneTransactionStatusLog $statusLog)
    {
        $entity = new SpyPaymentPayoneTransactionStatusLogOrderItem();
        $entity->setSpyPaymentPayoneTransactionStatusLog($statusLog);
        $entity->setIdSalesOrderItem($idSalesOrderItem);
        $entity->save();
    }

}
