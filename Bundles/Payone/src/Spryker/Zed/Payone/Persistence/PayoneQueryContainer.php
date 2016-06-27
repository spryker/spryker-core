<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payone\Persistence;

use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\Payone\Persistence\PayonePersistenceFactory getFactory()
 */
class PayoneQueryContainer extends AbstractQueryContainer implements PayoneQueryContainerInterface
{

    /**
     * @api
     *
     * @param int $transactionId
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneTransactionStatusLogQuery
     */
    public function createCurrentSequenceNumberQuery($transactionId)
    {
        $query = $this->getFactory()->createPaymentPayoneTransactionStatusLogQuery();
        $query->filterByTransactionId($transactionId)
              ->orderBySequenceNumber(Criteria::DESC);

        return $query;
    }

    /**
     * @api
     *
     * @deprecated use createCurrentSequenceNumberQuery instead
     *
     * @param int $transactionId
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneTransactionStatusLogQuery
     */
    public function getCurrentSequenceNumberQuery($transactionId)
    {
        trigger_error('Deprecated, use createCurrentSequenceNumberQuery() instead', E_USER_DEPRECATED);

        return $this->createCurrentSequenceNumberQuery($transactionId);
    }

    /**
     * @api
     *
     * @param int $transactionId
     *
     * @return \Orm\Zed\Payone\Persistence\Base\SpyPaymentPayoneQuery
     */
    public function createPaymentByTransactionIdQuery($transactionId)
    {
        $query = $this->getFactory()->createPaymentPayoneQuery();
        $query->filterByTransactionId($transactionId);

        return $query;
    }

    /**
     * @api
     *
     * @deprecated use createPaymentByTransactionIdQuery() instead
     *
     * @param int $transactionId
     *
     * @return \Orm\Zed\Payone\Persistence\Base\SpyPaymentPayoneQuery
     */
    public function getPaymentByTransactionIdQuery($transactionId)
    {
        trigger_error('Deprecated use createPaymentByTransactionIdQuery() instead', E_USER_DEPRECATED);

        return $this->createPaymentByTransactionIdQuery($transactionId);
    }

    /**
     * @api
     *
     * @param int $fkPayment
     * @param string $requestName
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneApiLogQuery
     */
    public function createApiLogByPaymentAndRequestTypeQuery($fkPayment, $requestName)
    {
        $query = $this->getFactory()->createPaymentPayoneApiLogQuery();
        $query->filterByFkPaymentPayone($fkPayment)
              ->filterByRequest($requestName);

        return $query;
    }

    /**
     * @api
     *
     * @deprecated use createApiLogByPaymentAndRequestTypeQuery() instead
     *
     * @param int $fkPayment
     * @param string $requestName
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneApiLogQuery
     */
    public function getApiLogByPaymentAndRequestTypeQuery($fkPayment, $requestName)
    {
        trigger_error('Deprecated use createApiLogByPaymentAndRequestTypeQuery() instead', E_USER_DEPRECATED);

        return $this->createApiLogByPaymentAndRequestTypeQuery($fkPayment, $requestName);
    }

    /**
     * @api
     *
     * @param int $idOrder
     *
     * @return \Orm\Zed\Payone\Persistence\Base\SpyPaymentPayoneQuery
     */
    public function createPaymentByOrderId($idOrder)
    {
        $query = $this->getFactory()->createPaymentPayoneQuery();
        $query->findByFkSalesOrder($idOrder);

        return $query;
    }

    /**
     * @api
     *
     * @deprecated use createPaymentByOrderId() instead
     *
     * @param int $idOrder
     *
     * @return \Orm\Zed\Payone\Persistence\Base\SpyPaymentPayoneQuery
     */
    public function getPaymentByOrderId($idOrder)
    {
        trigger_error('Deprecated use createPaymentByOrderId() instead', E_USER_DEPRECATED);

        return $this->createPaymentByOrderId($idOrder);
    }

    /**
     * @api
     *
     * @param int $idOrder
     * @param string $request
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneApiLogQuery
     */
    public function createApiLogsByOrderIdAndRequest($idOrder, $request)
    {
        $query = $this->getFactory()->createPaymentPayoneApiLogQuery()
            ->useSpyPaymentPayoneQuery()
            ->filterByFkSalesOrder($idOrder)
            ->endUse()
            ->filterByRequest($request)
            ->orderByCreatedAt(Criteria::DESC) //TODO: Index?
            ->orderByIdPaymentPayoneApiLog(Criteria::DESC);

        return $query;
    }

    /**
     * @api
     *
     * @deprecated use createApiLogsByOrderIdAndRequest() instead
     *
     * @param int $orderId
     * @param string $request
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneApiLogQuery
     */
    public function getApiLogsByOrderIdAndRequest($orderId, $request)
    {
        trigger_error('Deprecated use createApiLogsByOrderIdAndRequest() instead', E_USER_DEPRECATED);

        return $this->createApiLogsByOrderIdAndRequest($orderId, $request);
    }

    /**
     * @api
     *
     * @param int $paymentId
     *
     * @return \Orm\Zed\Payone\Persistence\Base\SpyPaymentPayoneQuery
     */
    public function createPaymentById($paymentId)
    {
        $query = $this->getFactory()->createPaymentPayoneQuery();
        $query->findByFkSalesOrder($paymentId);

        return $query;
    }

    /**
     * @api
     *
     * @deprecated use createPaymentById() instead
     *
     * @param int $paymentId
     *
     * @return \Orm\Zed\Payone\Persistence\Base\SpyPaymentPayoneQuery
     */
    public function getPaymentById($paymentId)
    {
        trigger_error('Deprecated use createPaymentById() instead', E_USER_DEPRECATED);

        return $this->createPaymentById($paymentId);
    }

    /**
     * @api
     *
     * @param int $idIdSalesOrder
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneTransactionStatusLog[]
     */
    public function createTransactionStatusLogsBySalesOrder($idIdSalesOrder)
    {
        $query = $this->getFactory()->createPaymentPayoneTransactionStatusLogQuery()
            ->useSpyPaymentPayoneQuery()
            ->filterByFkSalesOrder($idIdSalesOrder)
            ->endUse()
            ->orderByCreatedAt();

        return $query;
    }

    /**
     * @api
     *
     * @deprecated use createTransactionStatusLogsBySalesOrder() instead
     *
     * @param int $idIdSalesOrder
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneTransactionStatusLog[]
     */
    public function getTransactionStatusLogsBySalesOrder($idIdSalesOrder)
    {
        trigger_error('Deprecated use createTransactionStatusLogsBySalesOrder() instead', E_USER_DEPRECATED);

        return $this->createTransactionStatusLogsBySalesOrder($idIdSalesOrder);
    }

    /**
     * @api
     *
     * @param int $idSalesOrderItem
     * @param array $ids
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneTransactionStatusLogOrderItem[]
     */
    public function createTransactionStatusLogOrderItemsByLogIds($idSalesOrderItem, $ids)
    {
        $relations = $this->getFactory()->createPaymentPayoneTransactionStatusLogOrderItemQuery()
            ->filterByIdPaymentPayoneTransactionStatusLog($ids, Criteria::IN)
            ->filterByIdSalesOrderItem($idSalesOrderItem);

        return $relations;
    }

    /**
     * @api
     *
     * @deprecated use createTransactionStatusLogOrderItemsByLogIds() instead
     *
     * @param int $idSalesOrderItem
     * @param array $ids
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneTransactionStatusLogOrderItem[]
     */
    public function getTransactionStatusLogOrderItemsByLogIds($idSalesOrderItem, $ids)
    {
        trigger_error('Deprecated use createTransactionStatusLogOrderItemsByLogIds() instead', E_USER_DEPRECATED);

        return $this->createTransactionStatusLogOrderItemsByLogIds($idSalesOrderItem, $ids);
    }

    /**
     * @api
     *
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneApiLogQuery
     */
    public function createLastApiLogsByOrderId($idSalesOrder)
    {
        $query = $this->getFactory()->createPaymentPayoneApiLogQuery()
            ->useSpyPaymentPayoneQuery()
            ->filterByFkSalesOrder($idSalesOrder)
            ->endUse()
            ->orderByCreatedAt(Criteria::DESC)
            ->orderByIdPaymentPayoneApiLog(Criteria::DESC);

        return $query;
    }

    /**
     * @api
     *
     * @deprecated use createLastApiLogsByOrderId() instead
     *
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneApiLogQuery
     */
    public function getLastApiLogsByOrderId($idSalesOrder)
    {
        trigger_error('Deprecated use createLastApiLogsByOrderId() instead', E_USER_DEPRECATED);

        return $this->createLastApiLogsByOrderId($idSalesOrder);
    }

    /**
     * @api
     *
     * @param \Propel\Runtime\Collection\ObjectCollection $orders
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneApiLogQuery
     */
    public function createApiLogsByOrderIds($orders)
    {
        $ids = [];
        /** @var \Orm\Zed\Sales\Persistence\SpySalesOrder $order */
        foreach ($orders as $order) {
            $ids[] = $order->getIdSalesOrder();
        }

        $query = $this->getFactory()->createPaymentPayoneApiLogQuery()
            ->useSpyPaymentPayoneQuery()
            ->filterByFkSalesOrder($ids, Criteria::IN)
            ->endUse()
            ->orderByCreatedAt();

        return $query;
    }

    /**
     * @api
     *
     * @deprecated use createApiLogsByOrderIds() instead
     *
     * @param \Propel\Runtime\Collection\ObjectCollection $orders
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneApiLogQuery
     */
    public function getApiLogsByOrderIds($orders)
    {
        trigger_error('Deprecated use createApiLogsByOrderIds() instead', E_USER_DEPRECATED);

        return $this->createApiLogsByOrderIds($orders);
    }

    /**
     * @api
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder[] $orders
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneTransactionStatusLogQuery
     */
    public function createTransactionStatusLogsByOrderIds($orders)
    {
        $ids = [];
        foreach ($orders as $order) {
            $ids[] = $order->getIdSalesOrder();
        }

        $query = $this->getFactory()->createPaymentPayoneTransactionStatusLogQuery()
            ->useSpyPaymentPayoneQuery()
            ->filterByFkSalesOrder($ids, Criteria::IN)
            ->endUse()
            ->orderByCreatedAt();

        return $query;
    }

    /**
     * @api
     *
     * @deprecated use createTransactionStatusLogsByOrderIds() instead
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder[] $orders
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneTransactionStatusLogQuery
     */
    public function getTransactionStatusLogsByOrderIds($orders)
    {
        trigger_error('Deprecated, use createTransactionStatusLogsByOrderIds() instead', E_USER_DEPRECATED);

        return $this->createTransactionStatusLogsByOrderIds($orders);
    }

}
