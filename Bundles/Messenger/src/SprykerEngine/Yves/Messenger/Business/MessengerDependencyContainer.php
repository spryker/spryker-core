<?php

namespace SprykerEngine\Yves\Messenger\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractDependencyContainer;
use SprykerEngine\Zed\Messenger\Business\Model\MessengerInterface;

class MessengerDependencyContainer extends AbstractDependencyContainer
{
    /**
     * @var MessengerInterface
     */
    protected $messenger;

    /**
     * @return MessengerInterface
     */
    public function getMessenger()
    {
        if (null === $this->messenger) {
            $this->messenger = $this->getFactory()->createModelMessenger();
        }

        return $this->messenger;
    }
}
