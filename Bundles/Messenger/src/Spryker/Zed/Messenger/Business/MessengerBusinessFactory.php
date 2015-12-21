<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Messenger\Business;

use Spryker\Zed\Messenger\Business\Model\InMemoryMessageTray;
use Spryker\Zed\Messenger\Business\Model\MessageTrayInterface;
use Spryker\Zed\Messenger\Business\Model\SessionMessageTray;
use Spryker\Zed\Messenger\Dependency\Facade\MessengerToGlossaryInterface;
use Spryker\Zed\Messenger\MessengerDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Messenger\MessengerConfig;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @method MessengerConfig getConfig()
 */
class MessengerBusinessFactory extends AbstractBusinessFactory
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
        return new SessionMessageTray($this->getSession(), $this->getGlossaryFacade());
    }

    /**
     * @return SessionInterface
     */
    public function getSession()
    {
        return $this->getProvidedDependency(MessengerDependencyProvider::SESSION);
    }

    /**
     * @return MessengerToGlossaryInterface
     */
    public function getGlossaryFacade()
    {
        return $this->getProvidedDependency(MessengerDependencyProvider::FACADE_GLOSSARY);
    }

}
