<?php

namespace SprykerFeature\Zed\User\Business;

use Generated\Zed\Ide\AutoCompletion;
use Generated\Zed\Ide\FactoryAutoCompletion\UserBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractDependencyContainer;
use SprykerFeature\Zed\User\Business\Model\Installer;
use SprykerFeature\Zed\User\Persistence\UserQueryContainer;
use SprykerFeature\Zed\User\UserConfig;

/**
 * @method UserBusiness getFactory()
 * @method UserConfig getConfig()
 */
class UserDependencyContainer extends AbstractDependencyContainer
{
    /**
     * @return Model\User
     */
    public function getUserModel()
    {
        return $this->getFactory()->createModelUser(
            $this->getQueryContainer(),
            $this->getLocator()->application()->pluginSession(),
            $this->getConfig()
        );
    }

    /**
     * @return UserQueryContainer
     */
    private function getQueryContainer()
    {
        return $this->getLocator()->user()->queryContainer();
    }

    /**
     * @return Installer
     */
    public function getInstallerModel()
    {
        return $this->getFactory()->createModelInstaller(
            $this->getQueryContainer(),
            $this->getUserModel(),
            $this->getConfig()
        );
    }
}
