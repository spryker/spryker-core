<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GuiTable\Business;

use Spryker\Zed\GuiTable\Business\RequestBuilder\DataRequestBuilder;
use Spryker\Zed\GuiTable\Business\RequestBuilder\DataRequestBuilderInterface;
use Spryker\Zed\GuiTable\Business\ResponseFormatter\DataResponseFormatter;
use Spryker\Zed\GuiTable\Business\ResponseFormatter\DataResponseFormatterInterface;
use Spryker\Zed\GuiTable\Dependency\Facade\GuiTableToLocaleFacadeInterface;
use Spryker\Zed\GuiTable\Dependency\Service\GuiTableToUtilEncodingServiceInterface;
use Spryker\Zed\GuiTable\GuiTableDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\GuiTable\GuiTableConfig getConfig()
 */
class GuiTableBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\GuiTable\Business\RequestBuilder\DataRequestBuilderInterface
     */
    public function createDataRequestBuilder(): DataRequestBuilderInterface
    {
        return new DataRequestBuilder(
            $this->getUtilEncodingService(),
            $this->getLocaleFacade(),
            $this->getConfig(),
            $this->getRequestFilterValueNormalizerPlugins()
        );
    }

    /**
     * @return \Spryker\Zed\GuiTable\Business\ResponseFormatter\DataResponseFormatterInterface
     */
    public function createDataResponseFormatter(): DataResponseFormatterInterface
    {
        return new DataResponseFormatter(
            $this->getResponseColumnValueFormatterPlugins()
        );
    }

    /**
     * @return \Spryker\Zed\GuiTable\Dependency\Facade\GuiTableToLocaleFacadeInterface
     */
    public function getLocaleFacade(): GuiTableToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(GuiTableDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\GuiTable\Dependency\Service\GuiTableToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): GuiTableToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(GuiTableDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Zed\GuiTableExtension\Dependency\Plugin\RequestFilterValueNormalizerPluginInterface[]
     */
    public function getRequestFilterValueNormalizerPlugins(): array
    {
        return $this->getProvidedDependency(GuiTableDependencyProvider::PLUGINS_REQUEST_FILTER_VALUE_NORMALIZER);
    }

    /**
     * @return \Spryker\Zed\GuiTableExtension\Dependency\Plugin\ResponseColumnValueFormatterPluginInterface[]
     */
    public function getResponseColumnValueFormatterPlugins(): array
    {
        return $this->getProvidedDependency(GuiTableDependencyProvider::PLUGINS_RESPONSE_COLUMN_VALUE_FORMATTER);
    }
}
