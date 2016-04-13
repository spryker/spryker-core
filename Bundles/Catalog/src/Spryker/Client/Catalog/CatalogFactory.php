<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Catalog;

use Spryker\Client\Catalog\KeyBuilder\ProductResourceKeyBuilder;
use Spryker\Client\Catalog\Model\Catalog as ModelCatalog;
use Spryker\Client\Catalog\Model\Query\CategorySearchQuery;
use Spryker\Client\Catalog\Model\Query\FulltextSearchQuery;
use Spryker\Client\Catalog\Plugin\ResultFormatter\Elasticsearch\CatalogSearchResultFormatterPlugin;
use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Shared\Kernel\Store;

class CatalogFactory extends AbstractFactory
{

    /**
     * @return \Spryker\Client\Search\SearchClientInterface
     */
    public function getSearchClient()
    {
        return $this->getProvidedDependency(CatalogDependencyProvider::CLIENT_SEARCH);
    }

    /**
     * @param int $idCategory
     *
     * @return \Spryker\Client\Search\Model\Query\QueryInterface
     */
    public function createCategorySearchQuery($idCategory)
    {
        $searchQuery = new CategorySearchQuery($idCategory);

        return $searchQuery;
    }

    /**
     * @param string $searchString
     *
     * @return \Spryker\Client\Search\Model\Query\QueryInterface
     */
    public function createFulltextSearchQuery($searchString)
    {
        $searchQuery = new FulltextSearchQuery($searchString);

        return $searchQuery;
    }

    /**
     * @return \Spryker\Client\Search\Plugin\QueryExpanderPluginInterface[]
     */
    public function createCatalogSearchQueryExpanderPlugins()
    {
        return $this->getProvidedDependency(CatalogDependencyProvider::CATALOG_SEARCH_QUERY_EXPANDER_PLUGINS);
    }

    /**
     * @return \Spryker\Client\Search\Plugin\ResultFormatterPluginInterface[]
     */
    public function createCatalogSearchResultFormatters()
    {
        $resultFormatters = $this->getProvidedDependency(CatalogDependencyProvider::CATALOG_SEARCH_RESULT_FORMATTER_PLUGINS);
        $resultFormatters[] = new CatalogSearchResultFormatterPlugin();

        return $resultFormatters;
    }

    /**
     * @return \Spryker\Client\Catalog\Model\Catalog
     */
    public function createCatalogModel()
    {
        return new ModelCatalog(
            $this->createProductKeyBuilder(),
            $this->getKvStorage(),
            Store::getInstance()->getCurrentLocale()
        );
    }

    /**
     * @return mixed
     */
    protected function getKvStorage()
    {
        return $this->getProvidedDependency(CatalogDependencyProvider::KVSTORAGE);
    }

    /**
     * @return \Spryker\Shared\Collector\Code\KeyBuilder\KeyBuilderInterface
     */
    protected function createProductKeyBuilder()
    {
        return new ProductResourceKeyBuilder();
    }

}
