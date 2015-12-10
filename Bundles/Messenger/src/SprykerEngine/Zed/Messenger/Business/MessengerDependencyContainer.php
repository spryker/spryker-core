<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Messenger\Business;

use SprykerEngine\Zed\Messenger\Business\Model\InMemoryMessageTray;
use SprykerEngine\Zed\Messenger\Business\Model\MessageTrayInterface;
use SprykerEngine\Zed\Messenger\Business\Model\SessionMessageTray;
use Generated\Zed\Ide\FactoryAutoCompletion\MessengerBusiness;
use SprykerEngine\Zed\Messenger\MessengerDependencyProvider;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerEngine\Zed\Messenger\MessengerConfig;
use SprykerFeature\Zed\Glossary\Business\GlossaryFacade;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @method MessengerBusiness getFactory()
 * @method MessengerConfig getConfig()
 */
class MessengerDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return MessageTrayInterface
     */
    public function createMessageTray()
    {
        $messageTry = $this->getConfig()->getTray();
        if ($messageTry === MessengerConfig::IN_MEMORY_TRAY) {
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
        return $this->getProvidedDependency(MessengerDependencyProvider::SESSION);
    }

    /**
     * @return GlossaryFacade
     */
    public function getGlossaryFacade()
    {
        return $this->getProvidedDependency(MessengerDependencyProvider::FACADE_GLOSSARY);
    }

}
