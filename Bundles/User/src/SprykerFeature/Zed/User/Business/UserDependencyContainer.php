<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\User\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\UserBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\User\Business\Model\Installer;
use SprykerFeature\Zed\User\Business\Model\UserInterface;
use SprykerFeature\Zed\User\Persistence\UserQueryContainer;
use SprykerFeature\Zed\User\UserConfig;

/**
 * @method UserBusiness getFactory()
 * @method UserConfig getConfig()
 */
class UserDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return UserInterface
     */
    public function getUserModel()
    {
        return $this->getFactory()->createModelUser(
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
        return $this->getFactory()->createModelInstaller(
            $this->getQueryContainer(),
            $this->getUserModel(),
            $this->getConfig()
        );
    }

}
