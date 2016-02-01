<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Payone\Persistence;

use Orm\Zed\Payone\Persistence\SpyPaymentPayoneTransactionStatusLog;
use Orm\Zed\Payone\Persistence\SpyPaymentPayoneTransactionStatusLogOrderItem;

interface PayoneQueryContainerInterface
{

    /**
     * @param int $idPaymentPayone
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneTransactionStatusLogQuery
     */
    public function getCurrentSequenceNumberQuery($idPaymentPayone);

    /**
     * @param int $transactionId
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneQuery
     */
    public function getPaymentByTransactionIdQuery($transactionId);

    /**
     * @param int $fkPayment
     * @param string $requestType
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneApiLogQuery
     */
    public function getApiLogByPaymentAndRequestTypeQuery($fkPayment, $requestType);

    /**
     * @param int $orderId
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneQuery
     */
    public function getPaymentByOrderId($orderId);

    /**
     * @param int $orderId
     * @param string $request
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneApiLog
     */
    public function getApiLogsByOrderIdAndRequest($orderId, $request);

    /**
     * @param int $paymentId
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneQuery
     */
    public function getPaymentById($paymentId);

    /**
     * @param int $idSalesOrder
     *
     * @return SpyPaymentPayoneTransactionStatusLog[]
     */
    public function getTransactionStatusLogsBySalesOrder($idSalesOrder);

    /**
     * @param int $idSalesOrderItem
     * @param array $ids
     *
     * @return SpyPaymentPayoneTransactionStatusLogOrderItem[]
     */
    public function getTransactionStatusLogOrderItemsByLogIds($idSalesOrderItem, $ids);

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $orders
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneApiLogQuery
     */
    public function getApiLogsByOrderIds($orders);

    /**
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneApiLogQuery
     */
    public function getLastApiLogsByOrderId($idSalesOrder);

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $orders
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneTransactionStatusLogQuery
     */
    public function getTransactionStatusLogsByOrderIds($orders);

}
