<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\FlashMessenger\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\FlashMessengerBusiness;
use SprykerEngine\Zed\FlashMessenger\Business\Model\MessageTray;
use SprykerEngine\Zed\FlashMessenger\FlashMessengerDependencyProvider;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerEngine\Zed\FlashMessenger\FlashMessengerConfig;

/**
 * @method FlashMessengerBusiness getFactory()
 * @method FlashMessengerConfig getConfig()
 */
class FlashMessengerDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return MessageTray
     */
    public function createMessageTray()
    {
        $session = $this->getProvidedDependency(FlashMessengerDependencyProvider::SESSION);

        return $this->getFactory()->createModelMessageTray($session);
    }

}
