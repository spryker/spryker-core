<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Plugin\Config;

use Generated\Shared\Transfer\SearchConfigCacheTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\Search\Dependency\Plugin\SearchConfigInterface;
use Spryker\Shared\Search\SearchConstants;

/**
 * @method \Spryker\Client\Search\SearchFactory getFactory()
 */
class SearchConfig extends AbstractPlugin implements SearchConfigInterface
{

    /**
     * @var \Spryker\Client\Search\Dependency\Plugin\FacetConfigBuilderInterface
     */
    protected $facetConfigBuilder;

    /**
     * @var \Spryker\Client\Search\Dependency\Plugin\SortConfigBuilderInterface
     */
    protected $sortConfigBuilder;

    /**
     * @var \Spryker\Client\Search\Dependency\Plugin\PaginationConfigBuilderInterface
     */
    protected $paginationConfigBuilder;

    public function __construct()
    {
        $this->facetConfigBuilder = $this->getFactory()->createFacetConfigBuilder();
        $this->sortConfigBuilder = $this->getFactory()->createSortConfigBuilder();
        $this->paginationConfigBuilder = $this->getFactory()->createPaginationConfigBuilder();

        $this->buildSearchConfig();
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\FacetConfigBuilderInterface
     */
    public function getFacetConfigBuilder()
    {
        return $this->facetConfigBuilder;
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\SortConfigBuilderInterface
     */
    public function getSortConfigBuilder()
    {
        return $this->sortConfigBuilder;
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\PaginationConfigBuilderInterface
     */
    public function getPaginationConfigBuilder()
    {
        return $this->paginationConfigBuilder;
    }

    /**
     * @return void
     */
    protected function buildSearchConfig()
    {
        $searchConfigBuilder = $this
            ->getFactory()
            ->getSearchConfigBuilder();

        $searchConfigBuilder->buildFacetConfig($this->facetConfigBuilder);
        $searchConfigBuilder->buildSortConfig($this->sortConfigBuilder);
        $searchConfigBuilder->buildPaginationConfig($this->paginationConfigBuilder);

        $this->extendConfig();
    }

    /**
     * @return void
     */
    protected function extendConfig()
    {
        $searchConfigCacheTransfer = $this->getDynamicSearchConfig();

        $this->setDynamicFacets($searchConfigCacheTransfer);
        $this->setDynamicSorts($searchConfigCacheTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\SearchConfigCacheTransfer
     */
    protected function getDynamicSearchConfig()
    {
        $cacheData = $this
            ->getFactory()
            ->getStorageClient()
            ->get(SearchConstants::SEARCH_CONFIG_CACHE_KEY);

        $searchConfigCacheTransfer = new SearchConfigCacheTransfer();
        $searchConfigCacheTransfer->fromArray($cacheData, true);

        return $searchConfigCacheTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SearchConfigCacheTransfer $searchConfigCacheTransfer
     *
     * @return void
     */
    protected function setDynamicFacets(SearchConfigCacheTransfer $searchConfigCacheTransfer)
    {
        foreach ($searchConfigCacheTransfer->getFacetConfigs() as $facetConfigTransfer) {
            $this->facetConfigBuilder->addFacet($facetConfigTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\SearchConfigCacheTransfer $searchConfigCacheTransfer
     *
     * @return void
     */
    protected function setDynamicSorts(SearchConfigCacheTransfer $searchConfigCacheTransfer)
    {
        foreach ($searchConfigCacheTransfer->getSortConfigs() as $sortConfigTransfer) {
            $this->sortConfigBuilder->addSort($sortConfigTransfer);
        }
    }

}
