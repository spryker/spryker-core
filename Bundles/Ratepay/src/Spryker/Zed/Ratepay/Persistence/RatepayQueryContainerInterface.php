<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Persistence;

use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface RatepayQueryContainerInterface extends QueryContainerInterface
{
    /**
     * @api
     *
     * @return \Orm\Zed\Ratepay\Persistence\SpyPaymentRatepayQuery
     */
    public function queryPayments();

    /**
     * @api
     *
     * @param int $idPayment
     *
     * @return \Orm\Zed\Ratepay\Persistence\SpyPaymentRatepayQuery
     */
    public function queryPaymentById($idPayment);

    /**
     * @api
     *
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\Ratepay\Persistence\SpyPaymentRatepayQuery
     */
    public function queryPaymentBySalesOrderId($idSalesOrder);

    /**
     * @api
     *
     * @param int $idPayment
     * @param string $paymentType
     *
     * @return \Orm\Zed\Ratepay\Persistence\SpyPaymentRatepayQuery
     */
    public function queryPaymentByIdAndPaymentType($idPayment, $paymentType);

    /**
     * @api
     *
     * @return \Orm\Zed\Ratepay\Persistence\SpyPaymentRatepayLogQuery
     */
    public function queryPaymentLog();

    /**
     * @api
     *
     * @param int $idOrder
     *
     * @return \Orm\Zed\Ratepay\Persistence\SpyPaymentRatepayLogQuery
     */
    public function queryPaymentLogQueryBySalesOrderId($idOrder);

    /**
     * @api
     *
     * @param int $idOrder
     * @param string $message
     *
     * @return \Orm\Zed\Ratepay\Persistence\SpyPaymentRatepayLog
     */
    public function getLastLogRecordBySalesOrderIdAndMessage($idOrder, $message);
}
