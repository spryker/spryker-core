<?php

namespace SprykerFeature\Zed\Payone\Persistence;

use SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayoneTransactionStatusLogQuery;


interface PayoneQueryContainerInterface
{

    /**
     * @param $idPaymentPayone
     * @return SpyPaymentPayoneTransactionStatusLogQuery
     */
    public function getCurrentSequenceNumberQuery($idPaymentPayone);

}
