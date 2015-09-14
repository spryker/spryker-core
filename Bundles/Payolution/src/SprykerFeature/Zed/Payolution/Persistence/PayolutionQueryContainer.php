<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Persistence;

use Generated\Zed\Ide\FactoryAutoCompletion\PayolutionPersistence;
use Propel\Runtime\ActiveQuery\Criteria;
use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use SprykerFeature\Zed\Payolution\Persistence\Propel\Map\SpyPaymentPayolutionTransactionStatusLogTableMap;
use SprykerFeature\Zed\Payolution\Persistence\Propel\SpyPaymentPayolutionTransactionStatusLog;
use SprykerFeature\Zed\Payolution\Persistence\Propel\SpyPaymentPayolutionTransactionStatusLogQuery;
use SprykerFeature\Zed\Payolution\Persistence\Propel\SpyPaymentPayolution;
use SprykerFeature\Zed\Payolution\Persistence\Propel\SpyPaymentPayolutionQuery;

/**
 * @method PayolutionPersistence getFactory()
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

}
