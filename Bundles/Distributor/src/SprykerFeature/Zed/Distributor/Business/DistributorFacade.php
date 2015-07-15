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
     */
    public function touchItem($itemType, $idItem)
    {
        $this->getDependencyContainer()
            ->createItemWriter()
            ->touchItem($itemType, $idItem)
        ;
    }

    /**
     * @param MessengerInterface $messenger
     * @param array $itemTypes
     */
    public function distributeItems(MessengerInterface $messenger = null, $itemTypes = [])
    {
        $this->getDependencyContainer()
            ->createDistributor()
            ->distributeData($messenger, $itemTypes)
        ;
    }

    public function installItemTypes()
    {
        $this->getDependencyContainer()->createItemTypeInstaller()->install();
    }

    public function installReceiver()
    {
        $this->getDependencyContainer()->createReceiverInstaller()->install();
    }

}
