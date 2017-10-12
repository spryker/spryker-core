<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\Ratepay\Persistence\RatepayPersistenceFactory getFactory()
 */
class RatepayQueryContainer extends AbstractQueryContainer implements RatepayQueryContainerInterface
{
    /**
     * @api
     *
     * @return \Orm\Zed\Ratepay\Persistence\SpyPaymentRatepayQuery
     */
    public function queryPayments()
    {
        return $this->getFactory()->createPaymentRatepayQuery();
    }

    /**
     * @api
     *
     * @param int $idPayment
     *
     * @return \Orm\Zed\Ratepay\Persistence\SpyPaymentRatepayQuery
     */
    public function queryPaymentById($idPayment)
    {
        return $this
            ->queryPayments()
            ->filterByIdPaymentRatepay($idPayment);
    }

    /**
     * @api
     *
     * @param int $idPayment
     * @param string $paymentType
     *
     * @return \Orm\Zed\Ratepay\Persistence\SpyPaymentRatepayQuery
     */
    public function queryPaymentByIdAndPaymentType($idPayment, $paymentType)
    {
        return $this
            ->queryPayments()
            ->filterByIdPaymentRatepay($idPayment)
            ->filterByPaymentType($paymentType);
    }

    /**
     * @api
     *
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\Ratepay\Persistence\SpyPaymentRatepayQuery
     */
    public function queryPaymentBySalesOrderId($idSalesOrder)
    {
        return $this
            ->queryPayments()
            ->filterByFkSalesOrder($idSalesOrder);
    }

    /**
     * @api
     *
     * @return \Orm\Zed\Ratepay\Persistence\SpyPaymentRatepayLogQuery
     */
    public function queryPaymentLog()
    {
        return $this->getFactory()->createPaymentRatepayLogQuery();
    }

    /**
     * @api
     *
     * @param int $idOrder
     *
     * @return \Orm\Zed\Ratepay\Persistence\SpyPaymentRatepayLogQuery
     */
    public function queryPaymentLogQueryBySalesOrderId($idOrder)
    {
        return $this
            ->queryPaymentLog()
            ->filterByFkSalesOrder($idOrder);
    }

    /**
     * @api
     *
     * @param int $idOrder
     * @param string $message
     *
     * @return \Orm\Zed\Ratepay\Persistence\SpyPaymentRatepayLog
     */
    public function getLastLogRecordBySalesOrderIdAndMessage($idOrder, $message)
    {
        $logRecord = $this
            ->queryPaymentLogQueryBySalesOrderId($idOrder)
            ->filterByMessage($message)
            ->find()
            ->getLast();

        return $logRecord;
    }
}
