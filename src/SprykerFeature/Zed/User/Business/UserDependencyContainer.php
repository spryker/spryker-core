<?php

namespace SprykerFeature\Zed\User\Business;

use Generated\Zed\Ide\AutoCompletion;
use Generated\Zed\Ide\FactoryAutoCompletion\UserBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractDependencyContainer;
use SprykerFeature\Zed\User\Business\Model\Installer;
use SprykerFeature\Zed\User\Persistence\UserQueryContainer;

/**
 * @method UserBusiness getFactory()
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
            $this->getLocator(),
            $this->getSettings()
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
     * @return UserSettings
     */
    public function getSettings()
    {
        return $this->getFactory()->createUserSettings();
    }

    /**
     * @return Installer
     */
    public function getInstallerModel()
    {
        return $this->getFactory()->createModelInstaller(
            $this->getQueryContainer(),
            $this->getLocator(),
            $this->getSettings()
        );
    }
}
