<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UserLocale\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\UserLocale\Dependency\Facade\UserLocaleToLocaleFacadeBridgeInterface;
use Spryker\Zed\UserLocale\Dependency\Facade\UserLocaleToStoreInterface;
use Spryker\Zed\UserLocale\Dependency\Facade\UserLocaleToUserFacadeBridgeInterface;
use Spryker\Zed\UserLocale\UserLocaleDependencyProvider;

/**
 * @method \Spryker\Zed\UserLocale\UserLocaleConfig getConfig()
 * @method \Spryker\Zed\UserLocale\Business\UserLocaleFacadeInterface getFacade()
 */
class UserLocaleCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\UserLocale\Dependency\Facade\UserLocaleToLocaleFacadeBridgeInterface
     */
    public function getLocaleFacade(): UserLocaleToLocaleFacadeBridgeInterface
    {
        return $this->getProvidedDependency(UserLocaleDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\UserLocale\Dependency\Facade\UserLocaleToUserFacadeBridgeInterface
     */
    public function getUserFacade(): UserLocaleToUserFacadeBridgeInterface
    {
        return $this->getProvidedDependency(UserLocaleDependencyProvider::FACADE_USER);
    }

    /**
     * @return \Spryker\Zed\UserLocale\Dependency\Facade\UserLocaleToStoreInterface
     */
    public function getStore(): UserLocaleToStoreInterface
    {
        return $this->getProvidedDependency(UserLocaleDependencyProvider::STORE);
    }
}
