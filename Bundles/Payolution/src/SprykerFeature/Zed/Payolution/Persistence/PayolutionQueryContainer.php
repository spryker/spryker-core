<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Persistence;

use Generated\Zed\Ide\FactoryAutoCompletion\PayolutionPersistence;
use Propel\Runtime\ActiveQuery\Criteria;
use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use SprykerFeature\Zed\Payolution\Persistence\Propel\Map\SpyPaymentPayolutionTransactionRequestLogTableMap;
use SprykerFeature\Zed\Payolution\Persistence\Propel\Map\SpyPaymentPayolutionTransactionStatusLogTableMap;
use SprykerFeature\Zed\Payolution\Persistence\Propel\SpyPaymentPayolutionTransactionStatusLog;
use SprykerFeature\Zed\Payolution\Persistence\Propel\SpyPaymentPayolutionTransactionStatusLogQuery;
use SprykerFeature\Zed\Payolution\Persistence\Propel\SpyPaymentPayolution;
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
     * @return SpyPaymentPayolution
     */
    public function queryPaymentById($idPayment)
    {
        /** @var SpyPaymentPayolutionQuery $query */
        $query = SpyPaymentPayolutionQuery::create();

        return $query->requireOneByIdPaymentPayolution($idPayment);
    }

    /**
     * @param $idPayment
     *
     * @return SpyPaymentPayolutionTransactionStatusLog
     */
    public function queryLatestItemOfTransactionStatusLogByPaymentId($idPayment)
    {
        /** @var SpyPaymentPayolutionTransactionStatusLogQuery $query */
        $query = SpyPaymentPayolutionTransactionStatusLogQuery::create();
        $query->orderBy(SpyPaymentPayolutionTransactionStatusLogTableMap::COL_CREATED_AT, Criteria::DESC);

        return $query->requireOneByFkPaymentPayolution($idPayment);
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
     * @param string $paymentCode
     *
     * @return SpyPaymentPayolutionTransactionStatusLogQuery
     */
    public function queryLatestItemOfTransactionStatusLogByPaymentIdAndPaymentCode($idPayment, $paymentCode)
    {
        $query = SpyPaymentPayolutionTransactionStatusLogQuery::create();

        return $query
            // Payment code need to get checked in request log table
            ->joinSpyPaymentPayolutionTransactionRequestLog()
            ->useSpyPaymentPayolutionTransactionRequestLogQuery()
            ->filterByPaymentCode($paymentCode)
            ->endUse()
            // Filters for transaction log
            ->filterByFkPaymentPayolution($idPayment)
            ->addDescendingOrderByColumn(SpyPaymentPayolutionTransactionStatusLogTableMap::COL_CREATED_AT);
    }

}
