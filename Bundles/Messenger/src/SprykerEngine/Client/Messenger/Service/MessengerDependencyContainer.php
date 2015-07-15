<?php

namespace SprykerEngine\Client\Messenger\Service;

use SprykerEngine\Client\Kernel\Service\AbstractServiceDependencyContainer;
use SprykerEngine\Shared\Messenger\Business\Model\MessengerInterface;

class MessengerDependencyContainer extends AbstractServiceDependencyContainer
{

    /**
     * @return MessengerInterface
     */
    public function createMessenger()
    {
        return $this->getFactory()->createMessenger();
    }

}
