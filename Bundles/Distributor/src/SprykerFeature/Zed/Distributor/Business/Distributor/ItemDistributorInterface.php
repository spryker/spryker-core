<?php

namespace SprykerFeature\Zed\Distributor\Business\Distributor;

interface ItemDistributorInterface
{

    /**
     * @param $itemType
     * @param BatchIteratorInterface $batchIterator
     */
    public function distributeByType($itemType, BatchIteratorInterface $batchIterator);
}
