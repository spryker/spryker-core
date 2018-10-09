<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsContentWidget;

use Spryker\Zed\CmsContentWidget\Dependency\Facade\CmsContentWidgetToGlossaryBridge;
use Spryker\Zed\CmsContentWidget\Dependency\Service\CmsContentWidgetToUtilEncodingBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CmsContentWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PLUGINS_CMS_CONTENT_WIDGET_PARAMETER_MAPPERS = 'CMS CONTENT WIDGET PARAMETER MAPPER';
    public const FACADE_GLOSSARY = 'FACADE GLOSSARY';
    public const SERVICE_UTIL_ENCODING = 'UTIL ENCODING';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[static::PLUGINS_CMS_CONTENT_WIDGET_PARAMETER_MAPPERS] = function (Container $container) {
            return $this->getCmsContentWidgetParameterMapperPlugins($container);
        };

        $container[static::FACADE_GLOSSARY] = function (Container $container) {
            return new CmsContentWidgetToGlossaryBridge($container->getLocator()->glossary()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container[static::SERVICE_UTIL_ENCODING] = function (Container $container) {
            return new CmsContentWidgetToUtilEncodingBridge($container->getLocator()->utilEncoding()->service());
        };
    }

    /**
     * Cms content widget parameter plugins is used when collecting data to yves data store,
     * this mapping is needed because parameters provider to functions is not the same as we use to read from yves data store.
     * For example 'sku1' => 'primary key in redis', this will map sku to primary key and store together with cms content.
     *
     * Should be configured as key value pair where key is function name and value is concrete parameter mapper plugin.
     *
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\CmsContentWidget\Dependency\Plugin\CmsContentWidgetParameterMapperPluginInterface[]
     */
    protected function getCmsContentWidgetParameterMapperPlugins(Container $container)
    {
        return [];
    }
}
