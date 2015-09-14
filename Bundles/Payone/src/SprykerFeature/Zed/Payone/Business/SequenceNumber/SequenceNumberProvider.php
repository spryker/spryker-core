<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payone\Business\SequenceNumber;

use Propel\Runtime\Exception\PropelException;
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
        $current = $this->getCurrentSequenceNumber($transactionId);
        if ($current < 0) {
            return $current;
        }
        return $current + 1;
    }

    /**
     * @param string $transactionId
     *
     * @throws PropelException
     *
     * @return int
     */
    public function getCurrentSequenceNumber($transactionId)
    {
        $transactionEntity = $this->queryContainer
            ->getCurrentSequenceNumberQuery($transactionId)
            ->findOne();

        // If we have a transactionId but no status log we throw an exception
        if (!$transactionEntity || !$transactionEntity->getSequenceNumber()) {
            throw new PropelException('No current sequence number found.');
        }

        return $transactionEntity->getSequencenumber();
    }

}
