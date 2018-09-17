<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductPageSearch;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ProductPageSearch\Dependency\Client\ProductPageSearchToSearchClientInterface;
use Spryker\Client\ProductPageSearch\Plugin\Elasticsearch\Query\ProductConcretePageSearchQueryPluginInterface;
use Spryker\Client\ProductPageSearch\ProductConcreteReader\ProductConcreteReader;
use Spryker\Client\ProductPageSearch\ProductConcreteReader\ProductConcreteReaderInterface;

class ProductPageSearchFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ProductPageSearch\ProductConcreteReader\ProductConcreteReaderInterface
     */
    public function createProductConcreteReader(): ProductConcreteReaderInterface
    {
        return new ProductConcreteReader(
            $this->getSearchClient(),
            $this->getProductConcretePageSearchQueryPlugin(),
            $this->getProductConcretePageSearchQueryExpanderPlugins(),
            $this->getProductConcretePageSearchResultFormatterPlugins()
        );
    }

    /**
     * @return \Spryker\Client\ProductPageSearch\Dependency\Client\ProductPageSearchToSearchClientInterface
     */
    public function getSearchClient(): ProductPageSearchToSearchClientInterface
    {
        return $this->getProvidedDependency(ProductPageSearchDependencyProvider::CLIENT_SEARCH);
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\ResultFormatterPluginInterface[]
     */
    public function getProductConcretePageSearchResultFormatterPlugins(): array
    {
        return $this->getProvidedDependency(ProductPageSearchDependencyProvider::PLUGINS_PRODUCT_CONCRETE_PAGE_SEARCH_RESULT_FORMATTER);
    }

    /**
     * @return \Spryker\Client\ProductPageSearch\Plugin\Elasticsearch\Query\ProductConcretePageSearchQueryPluginInterface
     */
    public function getProductConcretePageSearchQueryPlugin(): ProductConcretePageSearchQueryPluginInterface
    {
        return $this->getProvidedDependency(ProductPageSearchDependencyProvider::PLUGIN_PRODUCT_CONCRETE_PAGE_SEARCH_QUERY);
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryExpanderPluginInterface[]
     */
    public function getProductConcretePageSearchQueryExpanderPlugins(): array
    {
        return $this->getProvidedDependency(ProductPageSearchDependencyProvider::PLUGINS_PRODUCT_CONCRETE_PAGE_SEARCH_QUERY_EXPANDER);
    }
}
