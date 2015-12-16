<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Kernel\Communication;

use Spryker\Zed\Messenger\Business\MessengerFacade;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Kernel\KernelDependencyProvider;

class KernelCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return MessengerFacade
     */
    public function createMessengerFacade()
    {
        return $this->getProvidedDependency(KernelDependencyProvider::FACADE_MESSENGER);
    }

}
