<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchHttp;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\SearchHttp\Dependency\Client\SearchHttpToCategoryStorageClientBridge;
use Spryker\Client\SearchHttp\Dependency\Client\SearchHttpToCustomerClientBridge;
use Spryker\Client\SearchHttp\Dependency\Client\SearchHttpToCustomerClientInterface;
use Spryker\Client\SearchHttp\Dependency\Client\SearchHttpToKernelAppClientBridge;
use Spryker\Client\SearchHttp\Dependency\Client\SearchHttpToKernelAppClientInterface;
use Spryker\Client\SearchHttp\Dependency\Client\SearchHttpToLocaleClientBridge;
use Spryker\Client\SearchHttp\Dependency\Client\SearchHttpToLocaleClientInterface;
use Spryker\Client\SearchHttp\Dependency\Client\SearchHttpToMoneyClientBridge;
use Spryker\Client\SearchHttp\Dependency\Client\SearchHttpToMoneyClientInterface;
use Spryker\Client\SearchHttp\Dependency\Client\SearchHttpToProductStorageClientBridge;
use Spryker\Client\SearchHttp\Dependency\Client\SearchHttpToProductStorageClientInterface;
use Spryker\Client\SearchHttp\Dependency\Client\SearchHttpToStorageClientBridge;
use Spryker\Client\SearchHttp\Dependency\Client\SearchHttpToStoreClientBridge;
use Spryker\Client\SearchHttp\Dependency\Service\SearchHttpToUtilEncodingServiceBridge;
use Spryker\Client\SearchHttp\Dependency\Service\SearchHttpToUtilEncodingServiceInterface;

/**
 * @method \Spryker\Client\SearchHttp\SearchHttpConfig getConfig()
 */
class SearchHttpDependencyProvider extends AbstractDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_STORAGE = 'CLIENT_STORAGE';

    /**
     * @var string
     */
    public const CLIENT_STORE = 'CLIENT_STORE';

    /**
     * @var string
     */
    public const CLIENT_LOCALE = 'CLIENT_LOCALE';

    /**
     * @var string
     */
    public const CLIENT_CUSTOMER = 'CLIENT_CUSTOMER';

    /**
     * @var string
     */
    public const CLIENT_KERNEL_APP = 'CLIENT_KERNEL_APP';

    /**
     * @var string
     */
    public const PLUGINS_SEARCH_CONFIG_BUILDER = 'PLUGINS_SEARCH_CONFIG_BUILDER';

    /**
     * @var string
     */
    public const PLUGINS_SEARCH_CONFIG_EXPANDER = 'PLUGINS_SEARCH_CONFIG_EXPANDER';

    /**
     * @var string
     */
    public const PLUGIN_FACET_CONFIG_TRANSFER_BUILDERS = 'PLUGIN_FACET_CONFIG_TRANSFER_BUILDERS';

    /**
     * @var string
     */
    public const PLUGIN_SORT_CONFIG_TRANSFER_BUILDERS = 'PLUGIN_SORT_CONFIG_TRANSFER_BUILDERS';

    /**
     * @var string
     */
    public const CLIENT_PRODUCT_STORAGE = 'CLIENT_PRODUCT_STORAGE';

    /**
     * @var string
     */
    public const CLIENT_MONEY = 'CLIENT_MONEY';

    /**
     * @var string
     */
    public const CLIENT_CATEGORY_STORAGE = 'CLIENT_CATEGORY_STORAGE';

    /**
     * @var string
     */
    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = $this->addStorageClient($container);
        $container = $this->addStoreClient($container);
        $container = $this->addLocaleClient($container);
        $container = $this->addCustomerClient($container);
        $container = $this->addKernelAppClient($container);
        $container = $this->addSearchConfigBuilderPlugins($container);
        $container = $this->addSearchConfigExpanderPlugins($container);
        $container = $this->addMoneyClient($container);
        $container = $this->addCategoryStorageClient($container);
        $container = $this->addProductStorageClient($container);
        $container = $this->addFacetConfigTransferBuilders($container);
        $container = $this->addSortConfigTransferBuilders($container);
        $container = $this->addUtilEncodingService($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_STORAGE, function (Container $container) {
            return new SearchHttpToStorageClientBridge($container->getLocator()->storage()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addStoreClient(Container $container): Container
    {
        $container->set(static::CLIENT_STORE, function (Container $container) {
            return new SearchHttpToStoreClientBridge($container->getLocator()->store()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addLocaleClient(Container $container): Container
    {
        $container->set(static::CLIENT_LOCALE, function (Container $container): SearchHttpToLocaleClientInterface {
            return new SearchHttpToLocaleClientBridge(
                $container->getLocator()->locale()->client(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addKernelAppClient(Container $container): Container
    {
        $container->set(static::CLIENT_KERNEL_APP, function (Container $container): SearchHttpToKernelAppClientInterface {
            return new SearchHttpToKernelAppClientBridge(
                $container->getLocator()->kernelApp()->client(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addSearchConfigBuilderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_SEARCH_CONFIG_BUILDER, function (): array {
            return $this->getSearchConfigBuilderPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addSearchConfigExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_SEARCH_CONFIG_EXPANDER, function (): array {
            return $this->getSearchConfigExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Client\SearchExtension\Dependency\Plugin\SearchConfigBuilderPluginInterface>
     */
    protected function getSearchConfigBuilderPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Client\SearchExtension\Dependency\Plugin\SearchConfigExpanderPluginInterface>
     */
    protected function getSearchConfigExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addMoneyClient(Container $container): Container
    {
        $container->set(static::CLIENT_MONEY, function (Container $container): SearchHttpToMoneyClientInterface {
            return new SearchHttpToMoneyClientBridge(
                $container->getLocator()->money()->client(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addCategoryStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_CATEGORY_STORAGE, function (Container $container): SearchHttpToCategoryStorageClientBridge {
            return new SearchHttpToCategoryStorageClientBridge(
                $container->getLocator()->categoryStorage()->client(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addProductStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_PRODUCT_STORAGE, function (Container $container): SearchHttpToProductStorageClientInterface {
            return new SearchHttpToProductStorageClientBridge(
                $container->getLocator()->productStorage()->client(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addFacetConfigTransferBuilders(Container $container): Container
    {
        $container->set(static::PLUGIN_FACET_CONFIG_TRANSFER_BUILDERS, function (): array {
            return $this->getFacetConfigTransferBuilders();
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addSortConfigTransferBuilders(Container $container): Container
    {
        $container->set(static::PLUGIN_SORT_CONFIG_TRANSFER_BUILDERS, function (): array {
            return $this->getSortConfigTransferBuilders();
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addUtilEncodingService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_ENCODING, function (Container $container): SearchHttpToUtilEncodingServiceInterface {
            return new SearchHttpToUtilEncodingServiceBridge(
                $container->getLocator()->utilEncoding()->service(),
            );
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Client\Catalog\Dependency\Plugin\FacetConfigTransferBuilderPluginInterface>
     */
    protected function getFacetConfigTransferBuilders(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Client\Catalog\Dependency\Plugin\SortConfigTransferBuilderPluginInterface>
     */
    protected function getSortConfigTransferBuilders(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addCustomerClient(Container $container): Container
    {
        $container->set(static::CLIENT_CUSTOMER, function (Container $container): SearchHttpToCustomerClientInterface {
            return new SearchHttpToCustomerClientBridge(
                $container->getLocator()->customer()->client(),
            );
        });

        return $container;
    }
}
