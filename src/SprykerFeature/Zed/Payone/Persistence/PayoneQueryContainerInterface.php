<?php

namespace SprykerFeature\Zed\Payone\Persistence;

use SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayoneTransactionStatusLogQuery;
use SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayoneQuery;


interface PayoneQueryContainerInterface
{

    /**
     * @param $idPaymentPayone
     * @return SpyPaymentPayoneTransactionStatusLogQuery
     */
    public function getCurrentSequenceNumberQuery($idPaymentPayone);

    /**
     * @param $transactionId
     * @return SpyPaymentPayoneQuery
     */
    public function getPaymentByTransactionIdQuery($transactionId);

}
