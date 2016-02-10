<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Transfer\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;
use Psr\Log\LoggerInterface;

/**
 * @method \Spryker\Zed\Transfer\Business\TransferBusinessFactory getFactory()
 */
class TransferFacade extends AbstractFacade implements TransferFacadeInterface
{

    /**
     * @param \Psr\Log\LoggerInterface $messenger
     *
     * @return void
     */
    public function generateTransferObjects(LoggerInterface $messenger)
    {
        $this->getFactory()->createTransferGenerator($messenger)->execute();
    }

    /**
     * @return void
     */
    public function deleteGeneratedTransferObjects()
    {
        $this->getFactory()->createTransferCleaner()->cleanDirectory();
    }

}
