<?php

namespace SprykerEngine\Zed\Transfer\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\TransferBusiness;
use SprykerEngine\Shared\Kernel\Messenger\MessengerInterface;
use SprykerEngine\Zed\Kernel\Business\AbstractDependencyContainer;
use SprykerEngine\Zed\Transfer\Business\Model\TransferGenerator;

/**
 * @method TransferBusiness getFactory()
 */
class TransferDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @return TransferGenerator
     */
    public function createTransferGenerator(MessengerInterface $messenger)
    {
        return $this->getFactory()->createModelTransferGenerator($messenger);
    }

}
