<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Persistence;

use Generated\Zed\Ide\FactoryAutoCompletion\PayolutionPersistence;
use Propel\Runtime\ActiveQuery\Criteria;
use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use Orm\Zed\Payolution\Persistence\SpyPaymentPayolutionTransactionRequestLogQuery;
use Orm\Zed\Payolution\Persistence\SpyPaymentPayolutionTransactionStatusLogQuery;
use Orm\Zed\Payolution\Persistence\SpyPaymentPayolutionQuery;

/**
 * @method PayolutionPersistence getFactory()
 */
class PayolutionQueryContainer extends AbstractQueryContainer implements PayolutionQueryContainerInterface
{

    /**
     * @return SpyPaymentPayolutionQuery
     */
    public function queryPayments()
    {
        return SpyPaymentPayolutionQuery::create();
    }

    /**
     * @param int $idPayment
     *
     * @return SpyPaymentPayolutionQuery
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
     * @return SpyPaymentPayolutionQuery
     */
    public function queryPaymentBySalesOrderId($idSalesOrder)
    {
        return $this
            ->queryPayments()
            ->filterByFkSalesOrder($idSalesOrder);
    }

    /**
     * @return SpyPaymentPayolutionTransactionStatusLogQuery
     */
    public function queryTransactionStatusLog()
    {
        return SpyPaymentPayolutionTransactionStatusLogQuery::create();
    }

    /**
     * @param int $idPayment
     *
     * @return SpyPaymentPayolutionTransactionStatusLogQuery
     */
    public function queryTransactionStatusLogByPaymentId($idPayment)
    {
        return $this
            ->queryTransactionStatusLog()
            ->filterByFkPaymentPayolution($idPayment);
    }

    /**
     * @param int $idPayment
     *
     * @return SpyPaymentPayolutionTransactionStatusLogQuery
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
     * @return SpyPaymentPayolutionTransactionStatusLogQuery
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
     * @return SpyPaymentPayolutionTransactionStatusLogQuery
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
     * @return SpyPaymentPayolutionTransactionStatusLogQuery
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
     * @return SpyPaymentPayolutionTransactionRequestLogQuery
     */
    public function queryTransactionRequestLogByPaymentId($idPayment)
    {
        $query = SpyPaymentPayolutionTransactionRequestLogQuery::create();

        return $query->filterByFkPaymentPayolution($idPayment);
    }

}
