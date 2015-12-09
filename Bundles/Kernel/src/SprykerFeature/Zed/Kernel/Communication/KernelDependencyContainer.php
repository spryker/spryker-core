<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Kernel\Communication;

use SprykerEngine\Zed\FlashMessenger\Business\FlashMessengerFacade;
use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use SprykerFeature\Zed\Kernel\KernelDependencyProvider;

class KernelDependencyContainer extends AbstractCommunicationDependencyContainer
{

    /**
     * @return FlashMessengerFacade
     */
    public function createFlashMessengerFacade()
    {
        return $this->getProvidedDependency(KernelDependencyProvider::FACADE_FLASH_MESSENGER);
    }

}
