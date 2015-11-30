<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Transfer\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use Psr\Log\LoggerInterface;

/**
 * @method TransferDependencyContainer getDependencyContainer()
 */
class TransferFacade extends AbstractFacade
{

    /**
     * @param LoggerInterface $messenger
     */
    public function generateTransferObjects(LoggerInterface $messenger)
    {
        $this->getDependencyContainer()->createTransferGenerator($messenger)->execute();
    }

    /**
     * @return void
     */
    public function deleteGeneratedTransferObjects()
    {
        $this->getDependencyContainer()->createTransferCleaner()->cleanDirectory();
    }

}
