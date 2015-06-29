<?php

namespace SprykerEngine\Client\Messenger\Service;

use SprykerEngine\Client\Kernel\Service\AbstractDependencyContainer;
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
