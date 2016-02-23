<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payolution\Persistence;

use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Shared\Payolution\PayolutionConstants;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\Payolution\Persistence\PayolutionPersistenceFactory getFactory()
 */
class PayolutionQueryContainer extends AbstractQueryContainer implements PayolutionQueryContainerInterface
{

    /**
     * @return \Orm\Zed\Payolution\Persistence\SpyPaymentPayolutionQuery
     */
    public function queryPayments()
    {
        return $this->getFactory()->createPaymentPayolutionQuery();
    }

    /**
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
     * @return \Orm\Zed\Payolution\Persistence\SpyPaymentPayolutionTransactionStatusLogQuery
     */
    public function queryTransactionStatusLog()
    {
        return $this->getFactory()->createPaymentPayolutionTransactionStatusLogQuery();
    }

    /**
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
     * @param int $idSalesOrder
     * @param string $paymentCode
     *
     * @return \Orm\Zed\Payolution\Persistence\SpyPaymentPayolutionTransactionStatusLogQuery
     */
    public function queryTransactionStatusLogBySalesOrderIdAndPaymentCodeLatestFirst($idSalesOrder, $paymentCode)
    {
        return $this->queryTransactionStatusLogBySalesOrderIdLatestFirst($idSalesOrder)
            // Payment code need to get checked in request log table
            ->joinSpyPaymentPayolutionTransactionRequestLog()
            ->useSpyPaymentPayolutionTransactionRequestLogQuery()
            ->filterByPaymentCode($paymentCode)
            ->endUse();
    }

    /**
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
