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
     * @param $itemType
     * @param array $itemIds
     *
     * @return bool
     */
    public function touchItems($itemType, array $itemIds)
    {

    }

    /**
     * @param $itemType
     *
     * @return bool
     */
    public function touchAllItemsByType($itemType)
    {

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
