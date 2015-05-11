<?php

namespace SprykerEngine\Zed\Transfer\Business;

use SprykerEngine\Shared\Kernel\Messenger\MessengerInterface;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;

/**
 * @method TransferDependencyContainer getDependencyContainer()
 */
class TransferFacade extends AbstractFacade
{

    /**
     * @param MessengerInterface $messenger
     */
    public function generateTransferObjects(MessengerInterface $messenger)
    {
        $this->getDependencyContainer()->createTransferGenerator($messenger)->execute();
    }

    public function deleteGeneratedTransferObjects()
    {
        $this->getDependencyContainer()->createTransferCleaner()->cleanDirectory();
    }
}
