<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payone\Persistence;

use Propel\Runtime\Collection\ObjectCollection;
use SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayoneApiLog;
use SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayoneTransactionStatusLog;
use SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayoneTransactionStatusLogOrderItem;
use SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayoneTransactionStatusLogQuery;
use SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayoneQuery;
use SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayoneApiLogQuery;

interface PayoneQueryContainerInterface
{

    /**
     * @param int $idPaymentPayone
     *
     * @return SpyPaymentPayoneTransactionStatusLogQuery
     */
    public function getCurrentSequenceNumberQuery($idPaymentPayone);

    /**
     * @param int $transactionId
     *
     * @return SpyPaymentPayoneQuery
     */
    public function getPaymentByTransactionIdQuery($transactionId);

    /**
     * @param int $fkPayment
     * @param string $requestType
     *
     * @return SpyPaymentPayoneApiLogQuery
     */
    public function getApiLogByPaymentAndRequestTypeQuery($fkPayment, $requestType);

    /**
     * @param int $orderId
     *
     * @return SpyPaymentPayoneQuery
     */
    public function getPaymentByOrderId($orderId);

    /**
     * @param int $orderId
     * @param string $request
     *
     * @return SpyPaymentPayoneApiLog
     */
    public function getApiLogsByOrderIdAndRequest($orderId, $request);

    /**
     * @param int $paymentId
     *
     * @return SpyPaymentPayoneQuery
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
     * @param ObjectCollection $orders
     *
     * @return SpyPaymentPayoneApiLogQuery
     */
    public function getApiLogsByOrderIds($orders);

    /**
     * @param int $idSalesOrder
     *
     * @return SpyPaymentPayoneApiLogQuery
     */
    public function getLastApiLogsByOrderId($idSalesOrder);

    /**
     * @param ObjectCollection $orders
     *
     * @return SpyPaymentPayoneTransactionStatusLogQuery
     */
    public function getTransactionStatusLogsByOrderIds($orders);

}
