<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GuiTable\Communication;

use Spryker\Zed\GuiTable\Communication\Setter\ConfigurationDefaultValuesSetter;
use Spryker\Zed\GuiTable\Communication\Setter\ConfigurationDefaultValuesSetterInterface;
use Spryker\Zed\GuiTable\Communication\Translator\ConfigurationTranslator;
use Spryker\Zed\GuiTable\Communication\Translator\ConfigurationTranslatorInterface;
use Spryker\Zed\GuiTable\Communication\Twig\GuiTableConfigurationFunctionProvider;
use Spryker\Zed\GuiTable\Dependency\Facade\GuiTableToTranslatorFacadeInterface;
use Spryker\Zed\GuiTable\Dependency\Service\GuiTableToUtilDateTimeServiceInterface;
use Spryker\Zed\GuiTable\Dependency\Service\GuiTableToUtilEncodingServiceInterface;
use Spryker\Zed\GuiTable\GuiTableDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\GuiTable\GuiTableConfig getConfig()
 * @method \Spryker\Zed\GuiTable\Business\GuiTableFacadeInterface getFacade()
 */
class GuiTableCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\GuiTable\Communication\Twig\GuiTableConfigurationFunctionProvider
     */
    public function createGuiTableConfigurationFunctionProvider(): GuiTableConfigurationFunctionProvider
    {
        return new GuiTableConfigurationFunctionProvider(
            $this->getUtilEncodingService(),
            $this->createConfigurationDefaultValuesSetter(),
            $this->createConfigurationTranslator()
        );
    }

    /**
     * @return \Spryker\Zed\GuiTable\Communication\Translator\ConfigurationTranslatorInterface
     */
    public function createConfigurationTranslator(): ConfigurationTranslatorInterface
    {
        return new ConfigurationTranslator($this->getTranslatorFacade());
    }

    /**
     * @return \Spryker\Zed\GuiTable\Communication\Setter\ConfigurationDefaultValuesSetterInterface
     */
    public function createConfigurationDefaultValuesSetter(): ConfigurationDefaultValuesSetterInterface
    {
        return new ConfigurationDefaultValuesSetter($this->getConfig());
    }

    /**
     * @return \Spryker\Zed\GuiTable\Dependency\Service\GuiTableToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): GuiTableToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(GuiTableDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Zed\GuiTable\Dependency\Service\GuiTableToUtilDateTimeServiceInterface
     */
    public function getUtilDateTimeService(): GuiTableToUtilDateTimeServiceInterface
    {
        return $this->getProvidedDependency(GuiTableDependencyProvider::SERVICE_UTIL_DATE_TIME);
    }

    /**
     * @return \Spryker\Zed\GuiTable\Dependency\Facade\GuiTableToTranslatorFacadeInterface
     */
    public function getTranslatorFacade(): GuiTableToTranslatorFacadeInterface
    {
        return $this->getProvidedDependency(GuiTableDependencyProvider::FACADE_TRANSLATOR);
    }
}
