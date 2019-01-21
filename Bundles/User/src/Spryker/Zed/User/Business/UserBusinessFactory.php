<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\User\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\User\Business\Model\Installer;
use Spryker\Zed\User\Business\Model\User;
use Spryker\Zed\User\Business\UserSession\UserSession;
use Spryker\Zed\User\Business\UserSession\UserSessionInterface;
use Spryker\Zed\User\UserDependencyProvider;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @method \Spryker\Zed\User\UserConfig getConfig()
 * @method \Spryker\Zed\User\Persistence\UserQueryContainerInterface getQueryContainer()
 */
class UserBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\User\Business\Model\UserInterface
     */
    public function createUserModel()
    {
        return new User(
            $this->getQueryContainer(),
            $this->getConfig(),
            $this->getPostSavePlugins()
        );
    }

    /**
     * @return \Spryker\Zed\User\Business\UserSession\UserSessionInterface
     */
    public function createUserSession(): UserSessionInterface
    {
        return new UserSession($this->getSessionClient());
    }

    /**
     * @return \Spryker\Zed\UserExtension\Dependency\Plugin\UserPostSavePluginInterface[]
     */
    public function getPostSavePlugins(): array
    {
        return $this->getProvidedDependency(UserDependencyProvider::PLUGINS_POST_SAVE);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Session\SessionInterface
     */
    public function getSessionClient(): SessionInterface
    {
        return $this->getProvidedDependency(UserDependencyProvider::CLIENT_SESSION);
    }

    /**
     * @return \Spryker\Zed\User\Business\Model\InstallerInterface
     */
    public function createInstallerModel()
    {
        return new Installer(
            $this->getQueryContainer(),
            $this->createUserModel(),
            $this->getConfig()
        );
    }
}
