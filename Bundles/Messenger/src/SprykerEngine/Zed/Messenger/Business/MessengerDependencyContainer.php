<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Messenger\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;

class MessengerDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @throws \Exception
     *
     * @return void
     */
    public function getMessenger()
    {
        throw new \Exception('Messenger must be implemented');
    }

}
