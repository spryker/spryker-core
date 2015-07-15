<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payone\Business\SequenceNumber;

use SprykerFeature\Zed\Payone\Persistence\PayoneQueryContainerInterface;

class SequenceNumberProvider implements SequenceNumberProviderInterface
{

    /**
     * @var PayoneQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param PayoneQueryContainerInterface $queryContainer
     */
    public function __construct(PayoneQueryContainerInterface $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param string $transactionId
     *
     * @return int
     */
    public function getNextSequenceNumber($transactionId)
    {
        return $this->getCurrentSequenceNumber($transactionId) + 1;
    }

    /**
     * @param string $transactionId
     *
     * @return int
     */
    public function getCurrentSequenceNumber($transactionId)
    {
        $transactionEntity = $this->queryContainer
            ->getCurrentSequenceNumberQuery($transactionId)
            ->findOne();

        //@todo if we have a transactionId but no status log we probably shouldn't continue
        if (!$transactionEntity || !$transactionEntity->getSequenceNumber()) {
            return 0;
        }

        return $transactionEntity->getSequencenumber();
    }

}
