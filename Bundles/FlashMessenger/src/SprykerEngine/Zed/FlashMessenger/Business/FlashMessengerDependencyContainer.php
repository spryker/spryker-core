<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\FlashMessenger\Business;

use SprykerEngine\Zed\FlashMessenger\Business\Model\InMemoryMessageTray;
use SprykerEngine\Zed\FlashMessenger\Business\Model\MessageTrayInterface;
use SprykerEngine\Zed\FlashMessenger\Business\Model\SessionMessageTray;
use Generated\Zed\Ide\FactoryAutoCompletion\FlashMessengerBusiness;
use SprykerEngine\Zed\FlashMessenger\FlashMessengerDependencyProvider;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerEngine\Zed\FlashMessenger\FlashMessengerConfig;
use SprykerFeature\Zed\Glossary\Business\GlossaryFacade;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @method FlashMessengerBusiness getFactory()
 * @method FlashMessengerConfig getConfig()
 */
class FlashMessengerDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return MessageTrayInterface
     */
    public function createMessageTray()
    {
        $messageTry = $this->getConfig()->getTray();
        if ($messageTry === FlashMessengerConfig::IN_MEMORY_TRAY) {
            return $this->createInMemoryMessageTray();
        }

        return $this->createSessionMessageTray();
    }

    /**
     * @return InMemoryMessageTray
     */
    public function createInMemoryMessageTray()
    {
        return new InMemoryMessageTray($this->getGlossaryFacade());
    }

    /**
     * @return SessionMessageTray
     */
    public function createSessionMessageTray()
    {
        return new SessionMessageTray($this->createSession(), $this->getGlossaryFacade());
    }

    /**
     * @return SessionInterface
     */
    public function getSession()
    {
        return $this->getProvidedDependency(FlashMessengerDependencyProvider::SESSION);
    }

    /**
     * @return GlossaryFacade
     */
    public function getGlossaryFacade()
    {
        return $this->getProvidedDependency(FlashMessengerDependencyProvider::FACADE_GLOSSARY);
    }

}
