<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductNew;

use Spryker\Client\Kernel\AbstractFactory;

class ProductNewFactory extends AbstractFactory
{
    /**
     * @param array $requestParameters
     *
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryInterface
     */
    public function getNewProductsQueryPlugin(array $requestParameters = [])
    {
        $newProductsQueryPlugin = $this->getProvidedDependency(ProductNewDependencyProvider::NEW_PRODUCTS_QUERY_PLUGIN);

        return $this->getSearchClient()->expandQuery(
            $newProductsQueryPlugin,
            $this->getNewProductsSearchQueryExpanderPlugins(),
            $requestParameters
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
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStore()
    {
        return $this->getProvidedDependency(ProductNewDependencyProvider::STORE);
    }

    /**
     * @return \Spryker\Client\ProductNew\ProductNewConfig
     */
    public function getConfig()
    {
        return parent::getConfig();
    }

    /**
     * @return \Spryker\Client\ProductNew\Dependency\Client\ProductNewToSearchClientInterface
     */
    public function getSearchClient()
    {
        return $this->getProvidedDependency(ProductNewDependencyProvider::CLIENT_SEARCH);
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryExpanderPluginInterface[]
     */
    protected function getNewProductsSearchQueryExpanderPlugins()
    {
        return $this->getProvidedDependency(ProductNewDependencyProvider::NEW_PRODUCTS_QUERY_EXPANDER_PLUGINS);
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\ResultFormatterPluginInterface[]
     */
    public function getNewProductsSearchResultFormatterPlugins()
    {
        return $this->getProvidedDependency(ProductNewDependencyProvider::NEW_PRODUCTS_RESULT_FORMATTER_PLUGINS);
    }
}
