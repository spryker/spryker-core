<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UserLocale\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\UserLocale\Dependency\Facade\UserLocaleToLocaleBridgeInterface;
use Spryker\Zed\UserLocale\Dependency\Facade\UserLocaleToUserBridgeInterface;
use Spryker\Zed\UserLocale\UserLocaleDependencyProvider;

/**
 * @method \Spryker\Zed\UserLocale\UserLocaleConfig getConfig()
 */
class UserLocaleCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\UserLocale\Dependency\Facade\UserLocaleToLocaleBridgeInterface
     */
    public function getLocaleFacade(): UserLocaleToLocaleBridgeInterface
    {
        return $this->getProvidedDependency(UserLocaleDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\UserLocale\Dependency\Facade\UserLocaleToUserBridgeInterface
     */
    public function getUserFacade(): UserLocaleToUserBridgeInterface
    {
        return $this->getProvidedDependency(UserLocaleDependencyProvider::FACADE_USER);
    }
}
