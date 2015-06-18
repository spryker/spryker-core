<?php

namespace SprykerFeature\Zed\QueueDistributor\Business\Distributor;

interface ItemDistributorInterface
{

    /**
     * @param $itemType
     * @param BatchIteratorInterface $batchIterator
     */
    public function distributeByType($itemType, BatchIteratorInterface $batchIterator);
}
