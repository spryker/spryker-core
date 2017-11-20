<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payolution\Persistence;

use Orm\Zed\Payolution\Persistence\Map\SpyPaymentPayolutionTransactionRequestLogTableMap;
use Orm\Zed\Payolution\Persistence\Map\SpyPaymentPayolutionTransactionStatusLogTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Propel;
use Spryker\Shared\Payolution\PayolutionConstants;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\Payolution\Persistence\PayolutionPersistenceFactory getFactory()
 */
class PayolutionQueryContainer extends AbstractQueryContainer implements PayolutionQueryContainerInterface
{
    /**
     * @api
     *
     * @return \Orm\Zed\Payolution\Persistence\SpyPaymentPayolutionQuery
     */
    public function queryPayments()
    {
        return $this->getFactory()->createPaymentPayolutionQuery();
    }

    /**
     * @api
     *
     * @param int $idPayment
     *
     * @return \Orm\Zed\Payolution\Persistence\SpyPaymentPayolutionQuery
     */
    public function queryPaymentById($idPayment)
    {
        return $this
            ->queryPayments()
            ->filterByIdPaymentPayolution($idPayment);
    }

    /**
     * @api
     *
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\Payolution\Persistence\SpyPaymentPayolutionQuery
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
     * @return \Orm\Zed\Payolution\Persistence\SpyPaymentPayolutionTransactionStatusLogQuery
     */
    public function queryTransactionStatusLog()
    {
        return $this->getFactory()->createPaymentPayolutionTransactionStatusLogQuery();
    }

    /**
     * @api
     *
     * @param int $idPayment
     *
     * @return \Orm\Zed\Payolution\Persistence\SpyPaymentPayolutionTransactionStatusLogQuery
     */
    public function queryTransactionStatusLogByPaymentId($idPayment)
    {
        return $this
            ->queryTransactionStatusLog()
            ->filterByFkPaymentPayolution($idPayment)
            ->filterByProcessingCode(PayolutionConstants::SUCCESSFUL_PRE_AUTHORIZATION_PROCESSING_CODE);
    }

    /**
     * @api
     *
     * @param int $idPayment
     *
     * @return \Orm\Zed\Payolution\Persistence\SpyPaymentPayolutionTransactionStatusLogQuery
     */
    public function queryTransactionStatusLogByPaymentIdLatestFirst($idPayment)
    {
        return $this
            ->queryTransactionStatusLogByPaymentId($idPayment)
            ->orderByIdPaymentPayolutionTransactionStatusLog(Criteria::DESC);
    }

    /**
     * @api
     *
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\Payolution\Persistence\SpyPaymentPayolutionTransactionStatusLogQuery
     */
    public function queryTransactionStatusLogBySalesOrderId($idSalesOrder)
    {
        return $this
            ->queryTransactionStatusLog()
            ->useSpyPaymentPayolutionQuery()
                ->filterByFkSalesOrder($idSalesOrder)
            ->endUse();
    }

    /**
     * @api
     *
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\Payolution\Persistence\SpyPaymentPayolutionTransactionStatusLogQuery
     */
    public function queryTransactionStatusLogBySalesOrderIdLatestFirst($idSalesOrder)
    {
        return $this
            ->queryTransactionStatusLogBySalesOrderId($idSalesOrder)
            ->orderByIdPaymentPayolutionTransactionStatusLog(Criteria::DESC);
    }

    /**
     * @api
     *
     * @param int $idSalesOrder
     * @param string $paymentCode
     *
     * @return \Orm\Zed\Payolution\Persistence\SpyPaymentPayolutionTransactionStatusLogQuery
     */
    public function queryTransactionStatusLogBySalesOrderIdAndPaymentCodeLatestFirst($idSalesOrder, $paymentCode)
    {
        return $this->queryTransactionStatusLogBySalesOrderIdLatestFirst($idSalesOrder)
            ->withColumn(SpyPaymentPayolutionTransactionRequestLogTableMap::COL_PAYMENT_CODE)
            ->addJoin(
                [
                    SpyPaymentPayolutionTransactionStatusLogTableMap::COL_IDENTIFICATION_TRANSACTIONID,
                    SpyPaymentPayolutionTransactionRequestLogTableMap::COL_PAYMENT_CODE,
                ],
                [
                    SpyPaymentPayolutionTransactionRequestLogTableMap::COL_TRANSACTION_ID,
                    Propel::getConnection()->quote($paymentCode),
                ]
            );
    }

    /**
     * @api
     *
     * @param int $idPayment
     *
     * @return \Orm\Zed\Payolution\Persistence\SpyPaymentPayolutionTransactionRequestLogQuery
     */
    public function queryTransactionRequestLogByPaymentId($idPayment)
    {
        $query = $this->getFactory()->createPaymentPayolutionTransactionRequestLogQuery();

        return $query->filterByFkPaymentPayolution($idPayment);
    }
}
