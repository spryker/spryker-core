<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Messenger\Business;

use Spryker\Shared\Messenger\MessengerConstants;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Messenger\Business\Model\InMemoryMessageTray;
use Spryker\Zed\Messenger\Business\Model\SessionMessageTray;
use Spryker\Zed\Messenger\MessengerDependencyProvider;

/**
 * @method \Spryker\Zed\Messenger\MessengerConfig getConfig()
 */
class MessengerBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\Messenger\Business\Model\MessageTrayInterface
     */
    public function createMessageTray()
    {
        $messageTry = $this->getConfig()->getTray();
        if ($messageTry === MessengerConstants::IN_MEMORY_TRAY) {
            return $this->createInMemoryMessageTray();
        }

        return $this->createSessionMessageTray();
    }

    /**
     * @return \Spryker\Zed\Messenger\Business\Model\InMemoryMessageTray
     */
    public function createInMemoryMessageTray()
    {
        return new InMemoryMessageTray($this->getGlossaryFacade());
    }

    /**
     * @return \Spryker\Zed\Messenger\Business\Model\SessionMessageTray
     */
    public function createSessionMessageTray()
    {
        return new SessionMessageTray($this->getSession(), $this->getGlossaryFacade());
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Session\SessionInterface
     */
    public function getSession()
    {
        return $this->getProvidedDependency(MessengerDependencyProvider::SESSION);
    }

    /**
     * @return \Spryker\Zed\Messenger\Dependency\Facade\MessengerToGlossaryInterface
     */
    public function getGlossaryFacade()
    {
        return $this->getProvidedDependency(MessengerDependencyProvider::FACADE_GLOSSARY);
    }

}
