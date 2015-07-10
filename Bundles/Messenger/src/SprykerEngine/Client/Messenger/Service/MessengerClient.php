<?php

namespace SprykerEngine\Client\Messenger\Service;

use SprykerEngine\Client\Kernel\Service\AbstractClient;
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
