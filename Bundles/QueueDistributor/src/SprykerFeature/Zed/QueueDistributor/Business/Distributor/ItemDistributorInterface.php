<?php

namespace SprykerFeature\Zed\StoreDistributor\Business\Distributor;

use SprykerEngine\Shared\Messenger\Business\Model\MessengerInterface;

interface ItemDistributorInterface
{

    /**
     * @param string $type
     * @param MessengerInterface $messenger
     */
    public function distributeByType($type, MessengerInterface $messenger);
}
