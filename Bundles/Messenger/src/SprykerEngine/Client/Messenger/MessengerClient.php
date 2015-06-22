<?php

namespace SprykerEngine\Client\Messenger;

use SprykerEngine\Client\Kernel\AbstractClient;
use SprykerEngine\Shared\Messenger\Business\Model\MessengerInterface;

class MessengerClient extends AbstractClient
{
    /**
     * @return MessengerInterface
     */
    public function createMessenger()
    {
        return $this->getDependencyContainer()->createMessenger();
    }

}
