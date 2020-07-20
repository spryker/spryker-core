<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GuiTable;

use Spryker\Zed\GuiTable\Dependency\Facade\GuiTableToLocaleFacadeBridge;
use Spryker\Zed\GuiTable\Dependency\Facade\GuiTableToTranslatorFacadeBridge;
use Spryker\Zed\GuiTable\Dependency\Service\GuiTableToUtilDateTimeServiceBridge;
use Spryker\Zed\GuiTable\Dependency\Service\GuiTableToUtilEncodingServiceBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\GuiTable\GuiTableConfig getConfig()
 */
class GuiTableDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_LOCALE = 'FACADE_LOCALE';
    public const FACADE_TRANSLATOR = 'FACADE_TRANSLATOR';

    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';
    public const SERVICE_UTIL_DATE_TIME = 'SERVICE_UTIL_DATE_TIME';

    public const PLUGINS_REQUEST_FILTER_VALUE_NORMALIZER = 'PLUGINS_REQUEST_FILTER_VALUE_NORMALIZER';
    public const PLUGINS_RESPONSE_COLUMN_VALUE_FORMATTER = 'PLUGINS_RESPONSE_COLUMN_VALUE_FORMATTER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = $this->addLocaleFacade($container);
        $container = $this->addTranslatorFacade($container);
        $container = $this->addUtilEncodingService($container);
        $container = $this->addRequestFilterValueNormalizerPlugins($container);
        $container = $this->addResponseColumnValueFormatterPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = $this->addTranslatorFacade($container);
        $container = $this->addUtilEncodingService($container);
        $container = $this->addUtilDateTimeService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addLocaleFacade(Container $container): Container
    {
        $container->set(static::FACADE_LOCALE, function (Container $container) {
            return new GuiTableToLocaleFacadeBridge(
                $container->getLocator()->locale()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addTranslatorFacade(Container $container): Container
    {
        $container->set(static::FACADE_TRANSLATOR, function (Container $container) {
            return new GuiTableToTranslatorFacadeBridge(
                $container->getLocator()->translator()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilEncodingService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_ENCODING, function (Container $container) {
            return new GuiTableToUtilEncodingServiceBridge(
                $container->getLocator()->utilEncoding()->service()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilDateTimeService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_DATE_TIME, function (Container $container) {
            return new GuiTableToUtilDateTimeServiceBridge(
                $container->getLocator()->utilDateTime()->service()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addRequestFilterValueNormalizerPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_REQUEST_FILTER_VALUE_NORMALIZER, function () {
            return $this->getRequestFilterValueNormalizerPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addResponseColumnValueFormatterPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_RESPONSE_COLUMN_VALUE_FORMATTER, function () {
            return $this->getResponseColumnValueFormatterPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Zed\GuiTableExtension\Dependency\Plugin\RequestFilterValueNormalizerPluginInterface[]
     */
    protected function getRequestFilterValueNormalizerPlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\GuiTableExtension\Dependency\Plugin\ResponseColumnValueFormatterPluginInterface[]
     */
    protected function getResponseColumnValueFormatterPlugins(): array
    {
        return [];
    }
}
