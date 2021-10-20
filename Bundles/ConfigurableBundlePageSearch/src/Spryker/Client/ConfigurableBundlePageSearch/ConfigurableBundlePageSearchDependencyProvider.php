<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ConfigurableBundlePageSearch;

use Spryker\Client\ConfigurableBundlePageSearch\Dependency\Client\ConfigurableBundlePageSearchToSearchClientBridge;
use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;

class ConfigurableBundlePageSearchDependencyProvider extends AbstractDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_SEARCH = 'CLIENT_SEARCH';

    /**
     * @var string
     */
    public const PLUGINS_CONFIGURABLE_BUNDLE_TEMPLATE_PAGE_SEARCH_RESULT_FORMATTER = 'PLUGINS_CONFIGURABLE_BUNDLE_TEMPLATE_PAGE_SEARCH_RESULT_FORMATTER';

    /**
     * @var string
     */
    public const PLUGINS_CONFIGURABLE_BUNDLE_TEMPLATE_PAGE_SEARCH_QUERY_EXPANDER = 'PLUGINS_CONFIGURABLE_BUNDLE_TEMPLATE_PAGE_SEARCH_QUERY_EXPANDER';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = parent::provideServiceLayerDependencies($container);
        $container = $this->addSearchClient($container);
        $container = $this->addConfigurableBundleTemplatePageSearchQueryExpanderPlugins($container);
        $container = $this->addConfigurableBundleTemplatePageSearchResultFormatterPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addSearchClient(Container $container): Container
    {
        $container->set(static::CLIENT_SEARCH, function (Container $container) {
            return new ConfigurableBundlePageSearchToSearchClientBridge(
                $container->getLocator()->search()->client()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addConfigurableBundleTemplatePageSearchResultFormatterPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_CONFIGURABLE_BUNDLE_TEMPLATE_PAGE_SEARCH_RESULT_FORMATTER, function () {
            return $this->getConfigurableBundleTemplatePageSearchResultFormatterPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addConfigurableBundleTemplatePageSearchQueryExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_CONFIGURABLE_BUNDLE_TEMPLATE_PAGE_SEARCH_QUERY_EXPANDER, function () {
            return $this->getConfigurableBundleTemplatePageSearchQueryExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Client\Search\Dependency\Plugin\ResultFormatterPluginInterface>
     */
    protected function getConfigurableBundleTemplatePageSearchResultFormatterPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Client\Search\Dependency\Plugin\QueryExpanderPluginInterface>
     */
    protected function getConfigurableBundleTemplatePageSearchQueryExpanderPlugins(): array
    {
        return [];
    }
}
