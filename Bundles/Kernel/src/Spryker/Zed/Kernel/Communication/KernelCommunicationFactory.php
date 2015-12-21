<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Kernel\Communication;

use Spryker\Zed\Kernel\Dependency\Facade\KernelToMessengerInterface;
use Spryker\Zed\Kernel\KernelDependencyProvider;

class KernelCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return KernelToMessengerInterface
     */
    public function createMessengerFacade()
    {
        return $this->getProvidedDependency(KernelDependencyProvider::FACADE_MESSENGER);
    }

}
