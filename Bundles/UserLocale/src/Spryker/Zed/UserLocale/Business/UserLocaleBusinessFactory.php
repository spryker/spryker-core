<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UserLocale\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\UserLocale\Business\Installer\Installer;
use Spryker\Zed\UserLocale\Business\Installer\InstallerInterface;
use Spryker\Zed\UserLocale\Dependency\Facade\UserLocaleToAclBridgeInterface;
use Spryker\Zed\UserLocale\Dependency\Facade\UserLocaleToLocaleBridgeInterface;
use Spryker\Zed\UserLocale\Dependency\Facade\UserLocaleToUserBridgeInterface;
use Spryker\Zed\UserLocale\UserLocaleDependencyProvider;

/**
 * @method \Spryker\Zed\UserLocale\UserLocaleConfig getConfig()
 */
class UserLocaleBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\UserLocale\Dependency\Facade\UserLocaleToLocaleBridgeInterface
     */
    public function getLocaleFacade(): UserLocaleToLocaleBridgeInterface
    {
        return $this->getProvidedDependency(UserLocaleDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\UserLocale\Business\Installer\InstallerInterface
     */
    public function createInstaller(): InstallerInterface
    {
        return new Installer(
            $this->getUserFacade(),
            $this->getLocaleFacade(),
            $this->getAclFacade(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\UserLocale\Dependency\Facade\UserLocaleToUserBridgeInterface
     */
    public function getUserFacade(): UserLocaleToUserBridgeInterface
    {
        return $this->getProvidedDependency(UserLocaleDependencyProvider::FACADE_USER);
    }

    /**
     * @return \Spryker\Zed\UserLocale\Dependency\Facade\UserLocaleToAclBridgeInterface
     */
    public function getAclFacade(): UserLocaleToAclBridgeInterface
    {
        return $this->getProvidedDependency(UserLocaleDependencyProvider::FACADE_ACL);
    }
}
