<?php

namespace SprykerEngine\Yves\Messenger\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractDependencyContainer;
use SprykerEngine\Shared\Messenger\Business\Model\MessengerInterface;

class MessengerDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @return MessengerInterface
     */
    public function getMessenger()
    {
        return $this->messenger = $this->getFactory()->createModelMessenger();
    }

}
