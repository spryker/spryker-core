<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductSetPageSearch;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ProductSetPageSearch\Plugin\Elasticsearch\Query\ProductSetPageSearchListQueryPlugin;

class ProductSetPageSearchFactory extends AbstractFactory
{
    /**
     * @param int|null $limit
     * @param int|null $offset
     *
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryInterface|\Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    public function createProductSetListQuery($limit = null, $offset = null)
    {
        $searchQuery = new ProductSetPageSearchListQueryPlugin($limit, $offset);

        $searchQuery = $this
            ->getSearchClient()
            ->expandQuery($searchQuery, $this->createProductSetListQueryExpanders());

        return $searchQuery;
    }

    /**
     * @return \Spryker\Client\Search\SearchClientInterface
     */
    public function getSearchClient()
    {
        return $this->getProvidedDependency(ProductSetPageSearchDependencyProvider::CLIENT_SEARCH);
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryExpanderPluginInterface[]
     */
    protected function createProductSetListQueryExpanders()
    {
        return $this->getProvidedDependency(ProductSetPageSearchDependencyProvider::PLUGIN_PRODUCT_SET_LIST_QUERY_EXPANDERS);
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\ResultFormatterPluginInterface[]
     */
    public function createProductSetListResultFormatters()
    {
        return $this->getProvidedDependency(ProductSetPageSearchDependencyProvider::PLUGIN_PRODUCT_SET_LIST_RESULT_FORMATTERS);
    }

    /**
     * @return \Spryker\Client\ProductSetPageSearch\Dependency\Client\ProductSetPageSearchToProductSetStorageClientInterface
     */
    public function getProductSetStorageClient()
    {
        return $this->getProvidedDependency(ProductSetPageSearchDependencyProvider::CLIENT_PRODUCT_SET_STORAGE);
    }
}
