<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Messenger\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerEngine\Shared\Messenger\Business\Model\MessengerInterface;

class MessengerDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return MessengerInterface
     */
    public function getMessenger()
    {
        return $this->getFactory()->createModelMessenger();
    }

}
