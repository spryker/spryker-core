<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Sdk\Messenger;

use SprykerEngine\Sdk\Kernel\AbstractSdk;
use SprykerEngine\Shared\Messenger\Business\Model\MessengerInterface;

class MessengerSdk extends AbstractSdk
{
    /**
     * @return MessengerInterface
     */
    public function createMessenger()
    {
        return $this->getDependencyContainer()->createMessenger();
    }
}
