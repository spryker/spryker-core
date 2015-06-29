<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Transfer\Business;

use SprykerEngine\Shared\Messenger\Business\Model\MessengerInterface;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use Psr\Log\LoggerInterface;

/**
 * @method TransferDependencyContainer getDependencyContainer()
 */
class TransferFacade extends AbstractFacade
{

    /**
     * @param MessengerInterface $messenger
     */
    public function generateTransferObjects(LoggerInterface $messenger)
    {
        $this->getDependencyContainer()->createTransferGenerator($messenger)->execute();
    }

    /**
     * @param MessengerInterface $messenger
     */
    public function generateTransferInterfaces(LoggerInterface $messenger)
    {
        $this->getDependencyContainer()->createTransferInterfaceGenerator($messenger)->execute();
    }

    public function deleteGeneratedTransferObjects()
    {
        $this->getDependencyContainer()->createTransferCleaner()->cleanDirectory();
    }
}
