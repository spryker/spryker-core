<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UserLocaleGui\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\UserLocaleGui\Communication\FormExpander\UserLocaleFormExpander;
use Spryker\Zed\UserLocaleGui\Communication\FormExpander\UserLocaleFormExpanderInterface;
use Spryker\Zed\UserLocaleGui\Communication\Mapper\LocaleMapper;
use Spryker\Zed\UserLocaleGui\Communication\Mapper\LocaleMapperInterface;
use Spryker\Zed\UserLocaleGui\Dependency\Facade\UserLocaleGuiToLocaleBridgeInterface;
use Spryker\Zed\UserLocaleGui\UserLocaleGuiDependencyProvider;

class UserLocaleGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\UserLocaleGui\Dependency\Facade\UserLocaleGuiToLocaleBridgeInterface
     */
    public function getLocaleFacade(): UserLocaleGuiToLocaleBridgeInterface
    {
        return $this->getProvidedDependency(UserLocaleGuiDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\UserLocaleGui\Communication\Mapper\LocaleMapperInterface
     */
    public function createLocaleMapper(): LocaleMapperInterface
    {
        return new LocaleMapper();
    }

    /**
     * @return \Spryker\Zed\UserLocaleGui\Communication\FormExpander\UserLocaleFormExpanderInterface
     */
    public function createUserLocaleFormExpander(): UserLocaleFormExpanderInterface
    {
        return new UserLocaleFormExpander($this->getLocaleFacade(), $this->createLocaleMapper());
    }
}
