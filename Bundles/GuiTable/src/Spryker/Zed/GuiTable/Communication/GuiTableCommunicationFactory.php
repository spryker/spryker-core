<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GuiTable\Communication;

use Spryker\Shared\GuiTable\Configuration\Expander\ConfigurationDefaultValuesExpander;
use Spryker\Shared\GuiTable\Configuration\Expander\ConfigurationDefaultValuesExpanderInterface;
use Spryker\Shared\GuiTable\Configuration\Translator\ConfigurationTranslatorInterface;
use Spryker\Shared\GuiTable\Dependency\Service\GuiTableToUtilDateTimeServiceInterface;
use Spryker\Shared\GuiTable\Dependency\Service\GuiTableToUtilEncodingServiceInterface;
use Spryker\Shared\GuiTable\GuiTableFactory;
use Spryker\Shared\GuiTable\GuiTableFactoryInterface;
use Spryker\Shared\GuiTable\Http\DataRequest\DataRequestBuilder;
use Spryker\Shared\GuiTable\Http\DataRequest\DataRequestBuilderInterface;
use Spryker\Shared\GuiTable\Http\DataResponse\DataResponseFormatter;
use Spryker\Shared\GuiTable\Http\DataResponse\DataResponseFormatterInterface;
use Spryker\Shared\GuiTable\Http\GuiTableDataRequestExecutor;
use Spryker\Shared\GuiTable\Http\GuiTableDataRequestExecutorInterface;
use Spryker\Shared\GuiTable\Http\HttpJsonResponseBuilder;
use Spryker\Shared\GuiTable\Http\HttpResponseBuilderInterface;
use Spryker\Shared\GuiTable\Normalizer\DateRangeRequestFilterValueNormalizer;
use Spryker\Shared\GuiTable\Normalizer\DateRangeRequestFilterValueNormalizerInterface;
use Spryker\Shared\GuiTable\Twig\GuiTableConfigurationFunction;
use Spryker\Zed\GuiTable\Communication\Translator\ConfigurationTranslator;
use Spryker\Zed\GuiTable\Dependency\Facade\GuiTableToTranslatorFacadeInterface;
use Spryker\Zed\GuiTable\GuiTableDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\GuiTable\GuiTableConfig getConfig()
 */
class GuiTableCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Shared\GuiTable\Twig\GuiTableConfigurationFunction
     */
    public function createGuiTableConfigurationFunction(): GuiTableConfigurationFunction
    {
        return new GuiTableConfigurationFunction(
            $this->getUtilEncodingService(),
            $this->createConfigurationDefaultValuesExpander(),
            $this->createConfigurationTranslator()
        );
    }

    /**
     * @return \Spryker\Shared\GuiTable\Configuration\Translator\ConfigurationTranslatorInterface
     */
    public function createConfigurationTranslator(): ConfigurationTranslatorInterface
    {
        return new ConfigurationTranslator($this->getTranslatorFacade());
    }

    /**
     * @return \Spryker\Shared\GuiTable\Configuration\Expander\ConfigurationDefaultValuesExpanderInterface
     */
    public function createConfigurationDefaultValuesExpander(): ConfigurationDefaultValuesExpanderInterface
    {
        return new ConfigurationDefaultValuesExpander($this->getConfig());
    }

    /**
     * @return \Spryker\Shared\GuiTable\GuiTableFactoryInterface
     */
    public function createGuiTableFactory(): GuiTableFactoryInterface
    {
        return new GuiTableFactory();
    }

    /**
     * @return \Spryker\Shared\GuiTable\Normalizer\DateRangeRequestFilterValueNormalizerInterface
     */
    public function createDateRangeRequestFilterValueNormalizer(): DateRangeRequestFilterValueNormalizerInterface
    {
        return new DateRangeRequestFilterValueNormalizer();
    }

    /**
     * @return \Spryker\Shared\GuiTable\Http\DataRequest\DataRequestBuilderInterface
     */
    public function createDataRequestBuilder(): DataRequestBuilderInterface
    {
        return new DataRequestBuilder(
            $this->getUtilEncodingService(),
            $this->getConfig(),
            $this->createDateRangeRequestFilterValueNormalizer()
        );
    }

    /**
     * @return \Spryker\Shared\GuiTable\Http\DataResponse\DataResponseFormatterInterface
     */
    public function createDataResponseFormatter(): DataResponseFormatterInterface
    {
        return new DataResponseFormatter($this->getUtilDateTimeService());
    }

    /**
     * @return \Spryker\Shared\GuiTable\Http\HttpResponseBuilderInterface
     */
    public function createHttpJsonResponseBuilder(): HttpResponseBuilderInterface
    {
        return new HttpJsonResponseBuilder();
    }

    /**
     * @return \Spryker\Shared\GuiTable\Http\GuiTableDataRequestExecutorInterface
     */
    public function createGuiTableDataRequestHandler(): GuiTableDataRequestExecutorInterface
    {
        return new GuiTableDataRequestExecutor(
            $this->createDataRequestBuilder(),
            $this->createDataResponseFormatter(),
            $this->createHttpJsonResponseBuilder()
        );
    }

    /**
     * @return \Spryker\Shared\GuiTable\Dependency\Service\GuiTableToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): GuiTableToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(GuiTableDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Shared\GuiTable\Dependency\Service\GuiTableToUtilDateTimeServiceInterface
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
