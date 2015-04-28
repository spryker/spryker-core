<?php

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
     * @return int
     */
    public function getNextSequenceNumber($idPaymentPayone)
    {
        return $this->getCurrentSequenceNumber($idPaymentPayone) + 1;
    }

    /**
     * @param string $transactionId
     * @return int
     */
    public function getCurrentSequenceNumber($idPaymentPayone)
    {
        $transactionEntity = $this->queryContainer
            ->getCurrentSequenceNumberQuery($idPaymentPayone)
            ->findOne();

        if (!$transactionEntity || !$transactionEntity->getSequenceNumber()) {
            return 0;
        }

        return $transactionEntity->getSequencenumber();
    }

}
