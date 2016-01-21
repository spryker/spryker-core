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
     * @deprecated Use getMessengerFacade() instead.
     *
     * @return KernelToMessengerInterface
     */
    public function createMessengerFacade()
    {
        trigger_error('Deprecated, use getMessengerFacade() instead.', E_USER_DEPRECATED);

        return $this->getMessengerFacade();
    }

    /**
     * @return KernelToMessengerInterface
     */
    public function getMessengerFacade()
    {
        return $this->getProvidedDependency(KernelDependencyProvider::FACADE_MESSENGER);
    }

}
