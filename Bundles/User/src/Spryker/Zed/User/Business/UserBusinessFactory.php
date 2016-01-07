<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\User\Business;

use Spryker\Zed\User\Business\Model\User;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\User\Business\Model\Installer;
use Spryker\Zed\User\Business\Model\UserInterface;
use Spryker\Zed\User\UserConfig;
use Spryker\Zed\User\UserDependencyProvider;
use Spryker\Zed\User\Persistence\UserQueryContainer;

/**
 * @method UserConfig getConfig()
 * @method UserQueryContainer getQueryContainer()
 */
class UserBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return UserInterface
     */
    public function createUserModel()
    {
        return new User(
            $this->getQueryContainer(),
            $this->getProvidedDependency(UserDependencyProvider::CLIENT_SESSION),
            $this->getConfig()
        );
    }

    /**
     * @return Installer
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
