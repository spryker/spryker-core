<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\User\Business;

use Spryker\Zed\User\Business\Model\User;
use Spryker\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use Spryker\Zed\User\Business\Model\Installer;
use Spryker\Zed\User\Business\Model\UserInterface;
use Spryker\Zed\User\Persistence\UserQueryContainer;
use Spryker\Zed\User\UserConfig;

/**
 * @method UserConfig getConfig()
 */
class UserDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return UserInterface
     */
    public function getUserModel()
    {
        return new User(
            $this->getQueryContainer(),
            $this->getLocator()->session()->client(),
            $this->getConfig()
        );
    }

    /**
     * @return UserQueryContainer
     */
    protected function getQueryContainer()
    {
        return $this->getLocator()->user()->queryContainer();
    }

    /**
     * @return Installer
     */
    public function getInstallerModel()
    {
        return new Installer(
            $this->getQueryContainer(),
            $this->getUserModel(),
            $this->getConfig()
        );
    }

}
