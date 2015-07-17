<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Yves\Messenger\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerEngine\Shared\Messenger\Business\Model\MessengerInterface;

class MessengerDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return MessengerInterface
     */
    public function getMessenger()
    {
        return $this->messenger = $this->getFactory()->createModelMessenger();
    }

}
