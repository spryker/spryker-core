<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Kernel\Communication;

use SprykerEngine\Zed\FlashMessenger\Business\FlashMessengerFacade;
use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use SprykerFeature\Zed\Kernel\GatewayDependencyProvider;

class GatewayDependencyContainer extends AbstractCommunicationDependencyContainer
{

    /**
     * @return FlashMessengerFacade
     */
    public function createFlashMessengerFacade()
    {
        return $this->getProvidedDependency(GatewayDependencyProvider::FACADE_FLASH_MESSENGER);
    }

}
