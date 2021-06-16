<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundlePageSearch;

use Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateQuery;
use Spryker\Zed\ConfigurableBundlePageSearch\Dependency\Facade\ConfigurableBundlePageSearchToConfigurableBundleFacadeBridge;
use Spryker\Zed\ConfigurableBundlePageSearch\Dependency\Facade\ConfigurableBundlePageSearchToEvenBehaviorFacadeBridge;
use Spryker\Zed\ConfigurableBundlePageSearch\Dependency\Facade\ConfigurableBundlePageSearchToProductImageFacadeBridge;
use Spryker\Zed\ConfigurableBundlePageSearch\Dependency\Service\ConfigurableBundlePageSearchToUtilEncodingServiceBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\ConfigurableBundlePageSearch\ConfigurableBundlePageSearchConfig getConfig()
 */
class ConfigurableBundlePageSearchDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_CONFIGURABLE_BUNDLE = 'FACADE_CONFIGURABLE_BUNDLE';
    public const FACADE_EVENT_BEHAVIOR = 'FACADE_EVENT_BEHAVIOR';

    /**
     * @deprecated Will be removed without replacement.
     */
    public const FACADE_SEARCH = 'FACADE_SEARCH';
    public const FACADE_PRODUCT_IMAGE = 'FACADE_PRODUCT_IMAGE';

    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';

    public const PROPEL_QUERY_CONFIGURABLE_BUNDLE_TEMPLATE = 'PROPEL_QUERY_CONFIGURABLE_BUNDLE_TEMPLATE';

    /**
     * @deprecated Use {@link \Spryker\Zed\ConfigurableBundlePageSearch\ConfigurableBundlePageSearchDependencyProvider::PLUGINS_CONFIGURABLE_BUNDLE_TEMPLATE_MAP_EXPANDER} instead.
     */
    public const PLUGINS_CONFIGURABLE_BUNDLE_TEMPLATE_PAGE_MAP_EXPANDER = 'PLUGINS_CONFIGURABLE_BUNDLE_TEMPLATE_PAGE_MAP_EXPANDER';
    public const PLUGINS_CONFIGURABLE_BUNDLE_TEMPLATE_PAGE_DATA_EXPANDER = 'PLUGINS_CONFIGURABLE_BUNDLE_TEMPLATE_PAGE_DATA_EXPANDER';

    public const PLUGINS_CONFIGURABLE_BUNDLE_TEMPLATE_MAP_EXPANDER = 'PLUGINS_CONFIGURABLE_BUNDLE_TEMPLATE_MAP_EXPANDER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addConfigurableBundleFacade($container);
        $container = $this->addConfigurableBundleFacade($container);
        $container = $this->addProductImageFacade($container);
        $container = $this->addUtilEncodingService($container);
        $container = $this->addConfigurableBundleTemplatePageDataExpanderPlugins($container);
        $container = $this->addConfigurableBundleTemplateMapExpanderPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container): Container
    {
        $container = parent::providePersistenceLayerDependencies($container);
        $container = $this->addConfigurableBundleTemplatePropelQuery($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);
        $container = $this->addEventBehaviorFacade($container);
        $container = $this->addConfigurableBundleTemplatePageMapExpanderPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addConfigurableBundleFacade(Container $container): Container
    {
        $container->set(static::FACADE_CONFIGURABLE_BUNDLE, function (Container $container) {
            return new ConfigurableBundlePageSearchToConfigurableBundleFacadeBridge(
                $container->getLocator()->configurableBundle()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addEventBehaviorFacade(Container $container): Container
    {
        $container->set(static::FACADE_EVENT_BEHAVIOR, function (Container $container) {
            return new ConfigurableBundlePageSearchToEvenBehaviorFacadeBridge(
                $container->getLocator()->eventBehavior()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductImageFacade(Container $container): Container
    {
        $container->set(static::FACADE_PRODUCT_IMAGE, function (Container $container) {
            return new ConfigurableBundlePageSearchToProductImageFacadeBridge(
                $container->getLocator()->productImage()->facade()
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
            return new ConfigurableBundlePageSearchToUtilEncodingServiceBridge(
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
    protected function addConfigurableBundleTemplatePropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_CONFIGURABLE_BUNDLE_TEMPLATE, $container->factory(function () {
            return SpyConfigurableBundleTemplateQuery::create();
        }));

        return $container;
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\ConfigurableBundlePageSearch\ConfigurableBundlePageSearchDependencyProvider::addConfigurableBundleTemplateMapExpanderPlugins()} instead.
     *
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addConfigurableBundleTemplatePageMapExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_CONFIGURABLE_BUNDLE_TEMPLATE_PAGE_MAP_EXPANDER, function () {
            return $this->getConfigurableBundleTemplatePageMapExpanderPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addConfigurableBundleTemplateMapExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_CONFIGURABLE_BUNDLE_TEMPLATE_MAP_EXPANDER, function () {
            return $this->getConfigurableBundleTemplateMapExpanderPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addConfigurableBundleTemplatePageDataExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_CONFIGURABLE_BUNDLE_TEMPLATE_PAGE_DATA_EXPANDER, function () {
            return $this->getConfigurableBundleTemplatePageDataExpanderPlugins();
        });

        return $container;
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Zed\ConfigurableBundlePageSearchExtension\Dependency\Plugin\ConfigurableBundleTemplatePageDataExpanderPluginInterface[]
     */
    protected function getConfigurableBundleTemplatePageMapExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundlePageSearchExtension\Dependency\Plugin\ConfigurableBundleTemplateMapExpanderPluginInterface[]
     */
    protected function getConfigurableBundleTemplateMapExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundlePageSearchExtension\Dependency\Plugin\ConfigurableBundleTemplatePageMapExpanderPluginInterface[]
     */
    protected function getConfigurableBundleTemplatePageDataExpanderPlugins(): array
    {
        return [];
    }
}
