<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UserLocaleGui\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\UserLocaleGui\Communication\FormExpander\DataProvider\LocaleChoiceFormDataProvider;
use Spryker\Zed\UserLocaleGui\Communication\FormExpander\UserLocaleFormExpander;
use Spryker\Zed\UserLocaleGui\Dependency\Facade\UserLocaleGuiToLocaleBridgeInterface;
use Spryker\Zed\UserLocaleGui\UserLocaleGuiDependencyProvider;
use Symfony\Component\Form\FormTypeInterface;

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
     * @return \Symfony\Component\Form\FormTypeInterface
     */
    public function createUserLocaleFormExpander(): FormTypeInterface
    {
        return new UserLocaleFormExpander();
    }

    /**
     * @return \Spryker\Zed\UserLocaleGui\Communication\FormExpander\DataProvider\LocaleChoiceFormDataProvider
     */
    public function createLocaleChoiceFormDataProvider(): LocaleChoiceFormDataProvider
    {
        return new LocaleChoiceFormDataProvider($this->getLocaleFacade());
    }
}
