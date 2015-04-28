<?php

namespace SprykerFeature\Zed\Payone\Business\SequenceNumber;


interface SequenceNumberProviderInterface
{

    /**
     * @param string $idPaymentPayone
     * @return int
     */
    public function getNextSequenceNumber($idPaymentPayone);

    /**
     * @param $idPaymentPayone
     * @return int
     */
    public function getCurrentSequenceNumber($idPaymentPayone);

}
