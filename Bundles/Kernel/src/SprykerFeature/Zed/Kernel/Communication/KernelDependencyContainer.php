<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Kernel\Communication;

use SprykerEngine\Zed\Messenger\Business\MessengerFacade;
use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use SprykerFeature\Zed\Kernel\KernelDependencyProvider;

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
