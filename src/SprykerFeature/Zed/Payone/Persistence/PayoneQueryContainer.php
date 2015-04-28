<?php

namespace SprykerFeature\Zed\Payone\Persistence;

use Propel\Runtime\ActiveQuery\Criteria;
use SprykerFeature\Zed\Payone\Persistence\Propel\PaymentPayoneTransactionQuery;
use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayoneTransactionStatusLogQuery;


class PayoneQueryContainer extends AbstractQueryContainer implements PayoneQueryContainerInterface
{

    /**
     * @todo think of doing it with MAX(sequence_number) ?
     * @param $idPaymentPayone
     * @return SpyPaymentPayoneTransactionStatusLogQuery
     */
    public function getCurrentSequenceNumberQuery($idPaymentPayone)
    {
        $query = SpyPaymentPayoneTransactionStatusLogQuery::create();
        $query->filterByFkPaymentPayone($idPaymentPayone)
              ->orderBySequencenumber(Criteria::DESC);

        return $query;
    }

}
