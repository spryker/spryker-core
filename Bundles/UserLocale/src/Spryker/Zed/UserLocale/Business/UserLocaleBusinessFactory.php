<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UserLocale\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\UserLocale\Business\UserExpander\UserExpander;
use Spryker\Zed\UserLocale\Business\UserExpander\UserExpanderInterface;
use Spryker\Zed\UserLocale\Business\UserLocaleReader\UserLocaleReader;
use Spryker\Zed\UserLocale\Business\UserLocaleReader\UserLocaleReaderInterface;
use Spryker\Zed\UserLocale\Dependency\Facade\UserLocaleToLocaleFacadeBridgeInterface;
use Spryker\Zed\UserLocale\Dependency\Facade\UserLocaleToUserFacadeBridgeInterface;
use Spryker\Zed\UserLocale\UserLocaleDependencyProvider;

/**
 * @method \Spryker\Zed\UserLocale\UserLocaleConfig getConfig()
 */
class UserLocaleBusinessFactory extends AbstractBusinessFactory
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
     * @return \Spryker\Zed\UserLocale\Business\UserExpander\UserExpanderInterface
     */
    public function createUserExpander(): UserExpanderInterface
    {
        return new UserExpander(
            $this->getLocaleFacade()
        );
    }

    /**
     * @return \Spryker\Zed\UserLocale\Business\UserLocaleReader\UserLocaleReaderInterface
     */
    public function createUserLocaleReader(): UserLocaleReaderInterface
    {
        return new UserLocaleReader($this->getUserFacade(), $this->getLocaleFacade());
    }
}
