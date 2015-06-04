<?php

namespace SprykerFeature\Zed\Payone\Persistence;

use Propel\Runtime\ActiveQuery\Criteria;
use SprykerFeature\Zed\Payone\Persistence\Propel\Base\SpyPaymentPayoneQuery;
use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayoneTransactionStatusLogQuery;
use SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayoneApiLogQuery;

class PayoneQueryContainer extends AbstractQueryContainer implements PayoneQueryContainerInterface
{

    /**
     * @todo think of doing it with MAX(sequence_number) ?
     * @param int $transactionId
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
     * @return SpyPaymentPayoneQuery
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
     * @return SpyPaymentPayoneApiLogQuery
     */
    public function getApiLogByPaymentAndRequestTypeQuery($fkPayment, $requestName)
    {
        $query = SpyPaymentPayoneApiLogQuery::create();
        $query->filterByFkPaymentPayone($fkPayment)
              ->filterByRequest($requestName);

        return $query;
    }

}
