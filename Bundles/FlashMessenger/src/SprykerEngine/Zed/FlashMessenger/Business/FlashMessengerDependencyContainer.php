<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\FlashMessenger\Business;

use SprykerEngine\Zed\FlashMessenger\Business\Model\InMemoryMessageTray;
use SprykerEngine\Zed\FlashMessenger\Business\Model\SessionMessageTray;
use Generated\Zed\Ide\FactoryAutoCompletion\FlashMessengerBusiness;
use SprykerEngine\Zed\FlashMessenger\Business\Model\MessageTray;
use SprykerEngine\Zed\FlashMessenger\FlashMessengerDependencyProvider;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerEngine\Zed\FlashMessenger\FlashMessengerConfig;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

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
        $messageTry = $this->getConfig()->getTray();

        if ($messageTry === FlashMessengerConfig::IN_MEMORY_TRAY) {
            return $this->createInMemoryMessageTray();
        } else {
            return $this->createSessionMessageTray();
        }
    }

    /**
     * @return InMemoryMessageTray
     */
    public function createInMemoryMessageTray()
    {
        return $this->getFactory()->createModelInMemoryMessageTray();
    }

    /**
     * @return SessionMessageTray
     */
    public function createSessionMessageTray()
    {
        return $this->getFactory()->createModelSessionMessageTray(
            $this->createSession()
        );
    }

    /**
     * @return SessionInterface
     */
    public function createSession()
    {
        return $this->getProvidedDependency(FlashMessengerDependencyProvider::SESSION);
    }

}
