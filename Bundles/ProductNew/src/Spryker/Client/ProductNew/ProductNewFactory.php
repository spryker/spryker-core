<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductNew;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ProductNew\Dependency\Client\ProductNewToLocaleClientInterface;
use Spryker\Client\ProductNew\Dependency\Client\ProductNewToStoreClientInterface;

class ProductNewFactory extends AbstractFactory
{
    /**
     * @param array<string, mixed> $requestParameters
     *
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryInterface
     */
    public function getNewProductsQueryPlugin(array $requestParameters = [])
    {
        $newProductsQueryPlugin = $this->getProvidedDependency(ProductNewDependencyProvider::NEW_PRODUCTS_QUERY_PLUGIN);

        return $this->getSearchClient()->expandQuery(
            $newProductsQueryPlugin,
            $this->getNewProductsSearchQueryExpanderPlugins(),
            $requestParameters,
        );
    }

    /**
     * @return \Spryker\Client\ProductNew\Dependency\Client\ProductNewToProductLabelStorageClientInterface
     */
    public function getProductLabelStorageClient()
    {
        return $this->getProvidedDependency(ProductNewDependencyProvider::CLIENT_PRODUCT_LABEL_STORAGE);
    }

    /**
     * @return \Spryker\Client\ProductNew\ProductNewConfig
     */
    public function getConfig()
    {
        /** @var \Spryker\Client\ProductNew\ProductNewConfig $config */
        $config = parent::getConfig();

        return $config;
    }

    /**
     * @return \Spryker\Client\ProductNew\Dependency\Client\ProductNewToSearchClientInterface
     */
    public function getSearchClient()
    {
        return $this->getProvidedDependency(ProductNewDependencyProvider::CLIENT_SEARCH);
    }

    /**
     * @return array<\Spryker\Client\Search\Dependency\Plugin\QueryExpanderPluginInterface>
     */
    protected function getNewProductsSearchQueryExpanderPlugins()
    {
        return $this->getProvidedDependency(ProductNewDependencyProvider::NEW_PRODUCTS_QUERY_EXPANDER_PLUGINS);
    }

    /**
     * @return array<\Spryker\Client\Search\Dependency\Plugin\ResultFormatterPluginInterface>
     */
    public function getNewProductsSearchResultFormatterPlugins()
    {
        return $this->getProvidedDependency(ProductNewDependencyProvider::NEW_PRODUCTS_RESULT_FORMATTER_PLUGINS);
    }

    /**
     * @return \Spryker\Client\ProductNew\Dependency\Client\ProductNewToStoreClientInterface
     */
    public function getStoreClient(): ProductNewToStoreClientInterface
    {
        return $this->getProvidedDependency(ProductNewDependencyProvider::CLIENT_STORE);
    }

    /**
     * @return \Spryker\Client\ProductNew\Dependency\Client\ProductNewToLocaleClientInterface
     */
    public function getLocaleClient(): ProductNewToLocaleClientInterface
    {
        return $this->getProvidedDependency(ProductNewDependencyProvider::CLIENT_LOCALE);
    }
}
