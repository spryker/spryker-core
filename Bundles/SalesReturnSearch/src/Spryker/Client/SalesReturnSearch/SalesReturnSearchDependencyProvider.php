<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SalesReturnSearch;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\SalesReturnSearch\Dependency\Client\SalesReturnSearchToSearchClientBridge;
use Spryker\Client\SalesReturnSearch\Plugin\Elasticsearch\Query\ReturnReasonSearchQueryPlugin;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;

/**
 * @method \Spryker\Client\SalesReturnSearch\SalesReturnSearchConfig getConfig()
 */
class SalesReturnSearchDependencyProvider extends AbstractDependencyProvider
{
    public const CLIENT_SEARCH = 'CLIENT_SEARCH';

    public const PLUGIN_RETURN_REASON_SEARCH_QUERY = 'PLUGIN_RETURN_REASON_SEARCH_QUERY';
    public const PLUGINS_RETURN_REASON_SEARCH_RESULT_FORMATTER = 'PLUGINS_RETURN_REASON_SEARCH_RESULT_FORMATTER';
    public const PLUGINS_RETURN_REASON_SEARCH_QUERY_EXPANDER = 'PLUGINS_RETURN_REASON_SEARCH_QUERY_EXPANDER';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = parent::provideServiceLayerDependencies($container);
        $container = $this->addSearchClient($container);
        $container = $this->addReturnReasonSearchQueryPlugin($container);
        $container = $this->addReturnReasonSearchResultFormatterPlugins($container);
        $container = $this->addReturnReasonSearchQueryExpanderPlugins($container);

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
            return new SalesReturnSearchToSearchClientBridge(
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
    protected function addReturnReasonSearchQueryPlugin(Container $container): Container
    {
        $container->set(static::PLUGIN_RETURN_REASON_SEARCH_QUERY, function () {
            return $this->createReturnReasonSearchQueryPlugin();
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addReturnReasonSearchResultFormatterPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_RETURN_REASON_SEARCH_RESULT_FORMATTER, function () {
            return $this->getReturnReasonSearchResultFormatterPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addReturnReasonSearchQueryExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_RETURN_REASON_SEARCH_QUERY_EXPANDER, function () {
            return $this->getReturnReasonSearchQueryExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    protected function createReturnReasonSearchQueryPlugin(): QueryInterface
    {
        return new ReturnReasonSearchQueryPlugin();
    }

    /**
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface[]
     */
    protected function getReturnReasonSearchResultFormatterPlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\QueryExpanderPluginInterface[]
     */
    protected function getReturnReasonSearchQueryExpanderPlugins(): array
    {
        return [];
    }
}
