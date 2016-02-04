<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Kernel\Communication;

use Spryker\Zed\Kernel\KernelDependencyProvider;

class KernelCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return \Spryker\Zed\Kernel\Dependency\Facade\KernelToMessengerInterface
     */
    public function getMessengerFacade()
    {
        return $this->getProvidedDependency(KernelDependencyProvider::FACADE_MESSENGER);
    }

}
