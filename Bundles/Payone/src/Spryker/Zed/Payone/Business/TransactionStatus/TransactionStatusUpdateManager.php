<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payone\Business\TransactionStatus;

use Generated\Shared\Transfer\PayoneStandardParameterTransfer;
use Orm\Zed\Payone\Persistence\SpyPaymentPayoneTransactionStatusLog;
use Orm\Zed\Payone\Persistence\SpyPaymentPayoneTransactionStatusLogOrderItem;
use Spryker\Shared\Payone\Dependency\TransactionStatusUpdateInterface;
use Spryker\Shared\Payone\PayoneTransactionStatusConstants;
use Spryker\Zed\Payone\Business\Api\TransactionStatus\TransactionStatusResponse;
use Spryker\Zed\Payone\Business\Key\HashGenerator;
use Spryker\Zed\Payone\Persistence\PayoneQueryContainerInterface;

class TransactionStatusUpdateManager
{

    /**
     * @var \Spryker\Zed\Payone\Persistence\PayoneQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Generated\Shared\Transfer\PayoneStandardParameterTransfer
     */
    protected $standardParameter;

    /**
     * @var \Spryker\Zed\Payone\Business\Key\HashGenerator
     */
    protected $hashGenerator;

    /**
     * @param \Spryker\Zed\Payone\Persistence\PayoneQueryContainerInterface $queryContainer
     * @param \Generated\Shared\Transfer\PayoneStandardParameterTransfer $standardParameter
     * @param \Spryker\Zed\Payone\Business\Key\HashGenerator $hashGenerator
     */
    public function __construct(
        PayoneQueryContainerInterface $queryContainer,
        PayoneStandardParameterTransfer $standardParameter,
        HashGenerator $hashGenerator
    ) {

        $this->queryContainer = $queryContainer;
        $this->standardParameter = $standardParameter;
        $this->hashGenerator = $hashGenerator;
    }

    /**
     * @param \Spryker\Shared\Payone\Dependency\TransactionStatusUpdateInterface $request
     *
     * @return \Spryker\Zed\Payone\Business\Api\TransactionStatus\TransactionStatusResponse
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
     * @param \Spryker\Shared\Payone\Dependency\TransactionStatusUpdateInterface $request
     *
     * @return void
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
        $entity->setReminderLevel($request->getReminderlevel());

        $entity->save();
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
     * @param \Spryker\Shared\Payone\Dependency\TransactionStatusUpdateInterface $request
     *
     * @return void
     */
    protected function transformCurrency(TransactionStatusUpdateInterface $request)
    {
        $balance = $request->getBalance();
        $balanceAmountInCents = round($balance * 100);
        $request->setBalance($balanceAmountInCents);

        $receivable = $request->getReceivable();
        $receivableAmountInCents = round($receivable * 100);
        $request->setReceivable($receivableAmountInCents);

        $price = $request->getPrice();
        $priceAmountInCents = round($price * 100);
        $request->setPrice($priceAmountInCents);
    }

    /**
     * @param \Spryker\Shared\Payone\Dependency\TransactionStatusUpdateInterface $request
     *
     * @return bool|\Spryker\Zed\Payone\Business\Api\TransactionStatus\TransactionStatusResponse
     */
    protected function validate(TransactionStatusUpdateInterface $request)
    {
        $systemHashedKey = $this->hashGenerator->hash($this->standardParameter->getKey());
        if ($request->getKey() !== $systemHashedKey) {
            return $this->createErrorResponse('Payone transaction status update: Given and internal key do not match!');
        }

        if ((int)$request->getAid() !== (int)$this->standardParameter->getAid()) {
            return $this->createErrorResponse('Payone transaction status update: Invalid Aid! System: ' . $this->standardParameter->getAid() . ' Request: ' . $request->getAid());
        }

        if ((int)$request->getPortalid() !== (int)$this->standardParameter->getPortalId()) {
            return $this->createErrorResponse('Payone transaction status update: Invalid Portalid! System: ' . $this->standardParameter->getPortalId() . ' Request: ' . $request->getPortalid());
        }

        return true;
    }

    /**
     * @param string $errorMessage
     *
     * @return \Spryker\Zed\Payone\Business\Api\TransactionStatus\TransactionStatusResponse
     */
    protected function createErrorResponse($errorMessage)
    {
        $response = new TransactionStatusResponse(false);
        $response->setErrorMessage($errorMessage);

        return $response;
    }

    /**
     * @return \Spryker\Zed\Payone\Business\Api\TransactionStatus\TransactionStatusResponse
     */
    protected function createSuccessResponse()
    {
        $response = new TransactionStatusResponse(true);

        return $response;
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
     * @param int $idSalesOrder
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
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

    /**
     * @param int $idSalesOrder
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    public function isPaymentCapture($idSalesOrder, $idSalesOrderItem)
    {
        $status = PayoneTransactionStatusConstants::TXACTION_CAPTURE;

        return $this->isPayment($idSalesOrder, $idSalesOrderItem, $status);
    }

    /**
     * @param int $idSalesOrder
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
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

    /**
     * @param int $idSalesOrder
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    public function isPaymentUnderpaid($idSalesOrder, $idSalesOrderItem)
    {
        $status = PayoneTransactionStatusConstants::TXACTION_UNDERPAID;

        return $this->isPayment($idSalesOrder, $idSalesOrderItem, $status);
    }

    /**
     * @param int $idSalesOrder
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    public function isPaymentAppointed($idSalesOrder, $idSalesOrderItem)
    {
        $status = PayoneTransactionStatusConstants::TXACTION_APPOINTED;

        return $this->isPayment($idSalesOrder, $idSalesOrderItem, $status);
    }

    /**
     * @param int $idSalesOrder
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    public function isPaymentOther($idSalesOrder, $idSalesOrderItem)
    {
        $statusLogs = $this->getUnprocessedTransactionStatusLogs($idSalesOrder, $idSalesOrderItem);
        if (empty($statusLogs)) {
            return false;
        }

        /** @var \Orm\Zed\Payone\Persistence\SpyPaymentPayoneTransactionStatusLog $statusLog */
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
     * @param int $idSalesOrder
     * @param int $idSalesOrderItem
     * @param string $status
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
     * @param int $idSalesOrder
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    private function hasUnprocessedTransactionStatusLogs($idSalesOrder, $idSalesOrderItem)
    {
        $records = $this->getUnprocessedTransactionStatusLogs($idSalesOrder, $idSalesOrderItem);

        return !empty($transactionStatusLogs);
    }

    /**
     * @param int $idSalesOrder
     * @param int $idSalesOrderItem
     * @param string $status
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneTransactionStatusLog|null
     */
    private function getFirstUnprocessedTransactionStatusLog($idSalesOrder, $idSalesOrderItem, $status)
    {
        $transactionStatusLogs = $this->getUnprocessedTransactionStatusLogs($idSalesOrder, $idSalesOrderItem);

        if (empty($transactionStatusLogs)) {
            return null;
        }

        /** @var \Orm\Zed\Payone\Persistence\SpyPaymentPayoneTransactionStatusLog $transactionStatusLog */
        $transactionStatusLog = array_shift($transactionStatusLogs);

        if ($transactionStatusLog->getStatus() !== $status) {
            return null;
        }

        return $transactionStatusLog;
    }

    /**
     * @param int $idSalesOrder
     * @param int $idSalesOrderItem
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneTransactionStatusLog[]
     */
    private function getUnprocessedTransactionStatusLogs($idSalesOrder, $idSalesOrderItem)
    {
        $transactionStatusLogs = $this->queryContainer->getTransactionStatusLogsBySalesOrder($idSalesOrder)->find();

        $ids = [];

        /** @var \Orm\Zed\Payone\Persistence\SpyPaymentPayoneTransactionStatusLog $transactionStatusLog */
        foreach ($transactionStatusLogs as $transactionStatusLog) {
            $ids[$transactionStatusLog->getIdPaymentPayoneTransactionStatusLog()] = $transactionStatusLog;
        }

        $relations = $this->queryContainer
            ->getTransactionStatusLogOrderItemsByLogIds($idSalesOrderItem, array_keys($ids))
            ->find();

        /** @var \Orm\Zed\Payone\Persistence\SpyPaymentPayoneTransactionStatusLogOrderItem $relation */
        foreach ($relations as $relation) {
            unset($ids[$relation->getIdPaymentPayoneTransactionStatusLog()]);
        }

        return $ids;
    }

    /**
     * @param int $idSalesOrderItem
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayoneTransactionStatusLog $statusLog
     *
     * @return void
     */
    protected function saveSpyPaymentPayoneTransactionStatusLogOrderItem($idSalesOrderItem, SpyPaymentPayoneTransactionStatusLog $statusLog)
    {
        $entity = new SpyPaymentPayoneTransactionStatusLogOrderItem();
        $entity->setSpyPaymentPayoneTransactionStatusLog($statusLog);
        $entity->setIdSalesOrderItem($idSalesOrderItem);
        $entity->save();
    }

}
