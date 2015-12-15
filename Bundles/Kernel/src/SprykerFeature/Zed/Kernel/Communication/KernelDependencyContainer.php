<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Kernel\Communication;

use Spryker\Zed\Messenger\Business\MessengerFacade;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use Spryker\Zed\Kernel\KernelDependencyProvider;

class KernelDependencyContainer extends AbstractCommunicationDependencyContainer
{

    /**
     * @return MessengerFacade
     */
    public function createMessengerFacade()
    {
        return $this->getProvidedDependency(KernelDependencyProvider::FACADE_MESSENGER);
    }

}
