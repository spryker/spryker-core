<?php

namespace SprykerFeature\Zed\Distributor\Business;

use SprykerEngine\Shared\Kernel\Messenger\MessengerInterface;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;

/**
 * @method DistributorDependencyContainer getDependencyContainer
 */
class DistributorFacade extends AbstractFacade
{

    /**
     * @param string $itemType
     * @param int $idItem
     *
     * @return int
     */
    public function touchItem($itemType, $idItem)
    {
        return $this->getDependencyContainer()
            ->createItemWriter()
            ->touchItem($itemType, $idItem)
        ;
    }

    /**
     * @param MessengerInterface $messenger
     * @param array $itemTypes
     */
    public function distributeItems(MessengerInterface $messenger, $itemTypes = [])
    {
        $this->getDependencyContainer()
            ->createQueueDistributor()
            ->distributeData($messenger, $itemTypes)
        ;
    }
}
