<?php

namespace SprykerFeature\Zed\Payone\Persistence;

use Propel\Runtime\ActiveQuery\Criteria;
use SprykerFeature\Zed\Payone\Persistence\Propel\Base\SpyPaymentPayoneQuery;
use SprykerFeature\Zed\Payone\Persistence\Propel\PaymentPayoneTransactionQuery;
use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayoneTransactionStatusLogQuery;


class PayoneQueryContainer extends AbstractQueryContainer implements PayoneQueryContainerInterface
{

    /**
     * @todo think of doing it with MAX(sequence_number) ?
     * @param $transactionId
     * @return SpyPaymentPayoneTransactionStatusLogQuery
     */
    public function getCurrentSequenceNumberQuery($transactionId)
    {
        $query = SpyPaymentPayoneTransactionStatusLogQuery::create();
        $query->filterByTransactionId($transactionId)
              ->orderBySequencenumber(Criteria::DESC);

        return $query;
    }

    /**
     * @param $transactionId
     * @return Propel\SpyPaymentPayoneQuery
     */
    public function getPaymentByTransactionIdQuery($transactionId)
    {
        $query = SpyPaymentPayoneQuery::create();
        $query->filterByTransactionId($transactionId);

        return $query;
    }

}
