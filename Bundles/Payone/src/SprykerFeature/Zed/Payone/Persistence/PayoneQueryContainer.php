<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payone\Persistence;

use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Collection\ObjectCollection;
use SprykerFeature\Zed\Payone\Persistence\Propel\Base\SpyPaymentPayoneQuery;
use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayoneApiLog;
use SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayoneTransactionStatusLog;
use SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayoneTransactionStatusLogOrderItem;
use SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayoneTransactionStatusLogOrderItemQuery;
use SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayoneTransactionStatusLogQuery;
use SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayoneApiLogQuery;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder;

class PayoneQueryContainer extends AbstractQueryContainer implements PayoneQueryContainerInterface
{

    /**
     * @param int $transactionId
     *
     * @return SpyPaymentPayoneTransactionStatusLogQuery
     */
    public function getCurrentSequenceNumberQuery($transactionId)
    {
        $query = SpyPaymentPayoneTransactionStatusLogQuery::create();
        $query->filterByTransactionId($transactionId)
              ->orderBySequenceNumber(Criteria::DESC);

        return $query;
    }

    /**
     * @param int $transactionId
     *
     * @return Propel\SpyPaymentPayoneQuery
     */
    public function getPaymentByTransactionIdQuery($transactionId)
    {
        $query = SpyPaymentPayoneQuery::create();
        $query->filterByTransactionId($transactionId);

        return $query;
    }

    /**
     * @param int $fkPayment
     * @param string $requestName
     *
     * @return SpyPaymentPayoneApiLogQuery
     */
    public function getApiLogByPaymentAndRequestTypeQuery($fkPayment, $requestName)
    {
        $query = SpyPaymentPayoneApiLogQuery::create();
        $query->filterByFkPaymentPayone($fkPayment)
              ->filterByRequest($requestName);

        return $query;
    }

    /**
     * @param int $orderId
     *
     * @return SpyPaymentPayoneQuery
     */
    public function getPaymentByOrderId($orderId)
    {
        $query = SpyPaymentPayoneQuery::create();
        $query->findByFkSalesOrder($orderId);

        return $query;
    }

    /**
     * @param int $orderId
     * @param string $request
     *
     * @return SpyPaymentPayoneApiLog
     */
    public function getApiLogsByOrderIdAndRequest($orderId, $request)
    {
        $query = SpyPaymentPayoneApiLogQuery::create()
            ->useSpyPaymentPayoneQuery()
            ->filterByFkSalesOrder($orderId)
            ->endUse()
            ->filterByRequest($request)
            ->orderByCreatedAt(Criteria::DESC) //TODO: Index?
            ->orderByIdPaymentPayoneApiLog(Criteria::DESC)
        ;

        return $query;
    }

    /**
     * @param int $paymentId
     * 
     * @return SpyPaymentPayoneQuery
     */
    public function getPaymentById($paymentId)
    {
        $query = SpyPaymentPayoneQuery::create();
        $query->findByFkSalesOrder($paymentId);

        return $query;
    }

    /**
     * @param int $idIdSalesOrder
     *
     * @return SpyPaymentPayoneTransactionStatusLog[]
     */
    public function getTransactionStatusLogsBySalesOrder($idIdSalesOrder)
    {
        $query = SpyPaymentPayoneTransactionStatusLogQuery::create()
            ->useSpyPaymentPayoneQuery()
            ->filterByFkSalesOrder($idIdSalesOrder)
            ->endUse()
            ->orderByCreatedAt()
        ;

        return $query;
    }

    /**
     * @param int $idSalesOrderItem
     * @param array $ids
     *
     * @return SpyPaymentPayoneTransactionStatusLogOrderItem[]
     */
    public function getTransactionStatusLogOrderItemsByLogIds($idSalesOrderItem, $ids)
    {
        $relations = SpyPaymentPayoneTransactionStatusLogOrderItemQuery::create()
            ->filterByIdPaymentPayoneTransactionStatusLog($ids)
            ->filterByIdSalesOrderItem($idSalesOrderItem)
        ;

        return $relations;
    }

    /**
     * @param ObjectCollection $orders
     *
     * @return SpyPaymentPayoneApiLogQuery
     */
    public function getApiLogsByOrderIds($orders)
    {
        $ids = [];
        /** @var SpySalesOrder $order */
        foreach ($orders as $order) {
            $ids[] = $order->getIdSalesOrder();
        }

        $query = SpyPaymentPayoneApiLogQuery::create()
            ->useSpyPaymentPayoneQuery()
            ->filterByFkSalesOrder($ids)
            ->endUse()
            ->orderByCreatedAt()
        ;

        return $query;
    }

    /**
     * @param int $idSalesOrder
     *
     * @return SpyPaymentPayoneApiLogQuery
     */
    public function getLastApiLogsByOrderId($idSalesOrder)
    {
        $query = SpyPaymentPayoneApiLogQuery::create()
            ->useSpyPaymentPayoneQuery()
            ->filterByFkSalesOrder($idSalesOrder)
            ->endUse()
            ->orderByCreatedAt(Criteria::DESC)
            ->orderByIdPaymentPayoneApiLog(Criteria::DESC)
        ;

        return $query;
    }

    /**
     * @param ObjectCollection $orders
     *
     * @return SpyPaymentPayoneTransactionStatusLogQuery
     */
    public function getTransactionStatusLogsByOrderIds($orders)
    {
        $ids = [];
        /** @var SpySalesOrder $order */
        foreach ($orders as $order) {
            $ids[] = $order->getIdSalesOrder();
        }

        $query = SpyPaymentPayoneTransactionStatusLogQuery::create()
            ->useSpyPaymentPayoneQuery()
            ->filterByFkSalesOrder($ids)
            ->endUse()
            ->orderByCreatedAt()
        ;

        return $query;
    }

}
