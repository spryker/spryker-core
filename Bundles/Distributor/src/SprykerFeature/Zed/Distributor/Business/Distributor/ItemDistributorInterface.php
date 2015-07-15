<?php

namespace SprykerFeature\Zed\Distributor\Business\Distributor;

interface ItemDistributorInterface
{

    /**
     * @param string $itemType
     * @param BatchIteratorInterface $batchIterator
     */
    public function distributeByType($itemType, BatchIteratorInterface $batchIterator);

}
