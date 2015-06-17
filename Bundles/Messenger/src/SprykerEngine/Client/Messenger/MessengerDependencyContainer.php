<?php

namespace SprykerEngine\Client\Messenger;

use SprykerEngine\Client\Kernel\AbstractDependencyContainer;
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
