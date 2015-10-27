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
     * @var int
     */
    protected $defaultEmptySequenceNumber;

    /**
     * @param PayoneQueryContainerInterface $queryContainer
     * @param int $defaultEmptySequenceNumber
     */
    public function __construct(PayoneQueryContainerInterface $queryContainer, $defaultEmptySequenceNumber)
    {
        $this->queryContainer = $queryContainer;

        $this->defaultEmptySequenceNumber = $defaultEmptySequenceNumber;
    }

    /**
     * @param string $transactionId
     *
     * @return int
     */
    public function getNextSequenceNumber($transactionId)
    {
        $current = $this->getCurrentSequenceNumber($transactionId);
        if ($current < 0) {
            return $current;
        }

        return $current + 1;
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

        // If we have a transactionId but no status log we return the configured default
        if (!$transactionEntity || !$transactionEntity->getSequenceNumber()) {
            return $this->defaultEmptySequenceNumber;
        }

        return $transactionEntity->getSequenceNumber();
    }

}
