<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Persistence;

use Generated\Zed\Ide\FactoryAutoCompletion\PayolutionPersistence;
use Propel\Runtime\ActiveQuery\Criteria;
use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use SprykerFeature\Zed\Payolution\Persistence\Propel\SpyPaymentPayolutionTransactionStatusLogQuery;
use SprykerFeature\Zed\Payolution\Persistence\Propel\SpyPaymentPayolutionQuery;

/**
 * @method PayolutionPersistence getFactory()
 *
 * @todo CD-408 methods need to return only (prefiltered) queries and not result(-sets)
 */
class PayolutionQueryContainer extends AbstractQueryContainer implements PayolutionQueryContainerInterface
{

    /**
     * @param int $idPayment
     *
     * @return SpyPaymentPayolutionQuery
     */
    public function queryPaymentById($idPayment)
    {
        /** @var SpyPaymentPayolutionQuery $query */
        $query = SpyPaymentPayolutionQuery::create();

        return $query->filterByIdPaymentPayolution($idPayment);
    }

    /**
     * @param int $idSalesOrder
     *
     * @return SpyPaymentPayolutionQuery
     */
    public function queryPaymentBySalesOrderId($idSalesOrder)
    {
        $query = SpyPaymentPayolutionQuery::create();

        return $query->filterByFkSalesOrder($idSalesOrder);
    }

    /**
     * @param int $idPayment
     *
     * @return SpyPaymentPayolutionTransactionStatusLogQuery
     */
    public function queryTransactionStatusLogByPaymentId($idPayment)
    {
        /** @var SpyPaymentPayolutionTransactionStatusLogQuery $query */
        $query = SpyPaymentPayolutionTransactionStatusLogQuery::create();

        return $query->filterByFkPaymentPayolution($idPayment);
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
     * @param int $idPayment
     * @param string $paymentCode
     *
     * @return SpyPaymentPayolutionTransactionStatusLogQuery
     */
    public function queryTransactionStatusLogByPaymentIdAndPaymentCodeLatestFirst($idPayment, $paymentCode)
    {
        return $this->queryTransactionStatusLogByPaymentIdLatestFirst($idPayment)
            // Payment code need to get checked in request log table
            ->joinSpyPaymentPayolutionTransactionRequestLog()
            ->useSpyPaymentPayolutionTransactionRequestLogQuery()
            ->filterByPaymentCode($paymentCode)
            ->endUse();
    }

    /**
     * @return SpyPaymentPayolutionTransactionStatusLogQuery
     */
    public function queryTransactionStatusLog()
    {
        return SpyPaymentPayolutionTransactionStatusLogQuery::create()->create();
    }

}
