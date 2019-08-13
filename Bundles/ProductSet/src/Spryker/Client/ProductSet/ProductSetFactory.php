<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductSet;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ProductSet\KeyBuilder\ProductSetResourceKeyBuilder;
use Spryker\Client\ProductSet\Mapper\ProductSetStorageMapper;
use Spryker\Client\ProductSet\Plugin\Elasticsearch\Query\ProductSetListQueryPlugin;
use Spryker\Client\ProductSet\Storage\ProductSetStorage;

class ProductSetFactory extends AbstractFactory
{
    /**
     * @param int|null $limit
     * @param int|null $offset
     *
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryInterface|\Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
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
     * @return \Spryker\Client\ProductSet\Storage\ProductSetStorage
     */
    public function createProductSetStorage()
    {
        return new ProductSetStorage(
            $this->getStorageClient(),
            $this->createProductSetResourceBuilder(),
            $this->getLocaleClient()->getCurrentLocale(),
            $this->createProductSetStorageMapper()
        );
    }

    /**
     * @return \Spryker\Shared\KeyBuilder\KeyBuilderInterface
     */
    protected function createProductSetResourceBuilder()
    {
        return new ProductSetResourceKeyBuilder();
    }

    /**
     * @return \Spryker\Client\Search\SearchClientInterface
     */
    public function getSearchClient()
    {
        return $this->getProvidedDependency(ProductSetDependencyProvider::CLIENT_SEARCH);
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\ResultFormatterPluginInterface[]
     */
    public function createProductSetListResultFormatters()
    {
        return $this->getProvidedDependency(ProductSetDependencyProvider::PLUGIN_PRODUCT_SET_LIST_RESULT_FORMATTERS);
    }

    /**
     * @return \Spryker\Client\ProductSet\Mapper\ProductSetStorageMapperInterface
     */
    public function createProductSetStorageMapper()
    {
        return new ProductSetStorageMapper();
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryExpanderPluginInterface[]
     */
    protected function createProductSetListQueryExpanders()
    {
        return $this->getProvidedDependency(ProductSetDependencyProvider::PLUGIN_PRODUCT_SET_LIST_QUERY_EXPANDERS);
    }

    /**
     * @return \Spryker\Client\ProductSet\Dependency\Client\ProductSetToStorageInterface
     */
    protected function getStorageClient()
    {
        return $this->getProvidedDependency(ProductSetDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Client\Product\Dependency\Client\ProductToLocaleInterface
     */
    public function getLocaleClient()
    {
        return $this->getProvidedDependency(ProductSetDependencyProvider::CLIENT_LOCALE);
    }
}
