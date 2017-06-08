<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductSetCollector;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ProductSetCollector\Plugin\Elasticsearch\Query\ProductSetListQueryPlugin;

class ProductSetCollectorFactory extends AbstractFactory
{

    /**
     * @param int|null $limit
     * @param int|null $offset
     *
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryInterface
     */
    public function createProductSetListQuery($limit = null, $offset = null)
    {
        $searchQuery = new ProductSetListQueryPlugin($limit, $offset);

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
        return $this->getProvidedDependency(ProductSetCollectorDependencyProvider::CLIENT_SEARCH);
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\ResultFormatterPluginInterface[]
     */
    public function createProductSetListResultFormatters()
    {
        return $this->getProvidedDependency(ProductSetCollectorDependencyProvider::PLUGIN_PRODUCT_SET_LIST_RESULT_FORMATTERS);
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryExpanderPluginInterface[]
     */
    protected function createProductSetListQueryExpanders()
    {
        return $this->getProvidedDependency(ProductSetCollectorDependencyProvider::PLUGIN_PRODUCT_SET_LIST_QUERY_EXPANDERS);
    }

}
