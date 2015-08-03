<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payone\Business\SequenceNumber;

interface SequenceNumberProviderInterface
{

    /**
     * @param string $transactionId
     *
     * @return int
     */
    public function getNextSequenceNumber($transactionId);

    /**
     * @param string $transactionId
     *
     * @return int
     */
    public function getCurrentSequenceNumber($transactionId);

}
