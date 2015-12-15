<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Transfer\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;
use Psr\Log\LoggerInterface;

/**
 * @method TransferDependencyContainer getDependencyContainer()
 */
class TransferFacade extends AbstractFacade
{

    /**
     * @param LoggerInterface $messenger
     *
     * @return void
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
