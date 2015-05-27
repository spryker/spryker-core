<?php

namespace SprykerEngine\Sdk\Messenger;

use SprykerEngine\Sdk\Kernel\AbstractDependencyContainer;
use SprykerEngine\Shared\Messenger\Business\Model\MessengerInterface;

class MessengerDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @return MessengerInterface
     */
    public function createMessenger()
    {
        return $this->getFactory()->createMessenger();
    }

}
