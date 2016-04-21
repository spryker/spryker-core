<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Plugin\Config;

use Generated\Shared\Transfer\FacetConfigTransfer;
use Generated\Shared\Transfer\SortConfigTransfer;
use Spryker\Client\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Client\Search\SearchFactory getFactory()
 */
class SearchConfig extends AbstractPlugin implements SearchConfigInterface
{

    /**
     * @var \Spryker\Client\Search\Plugin\Config\FacetConfigBuilderInterface
     */
    protected $facetConfigBuilder;

    /**
     * @var \Spryker\Client\Search\Plugin\Config\SortConfigBuilderInterface
     */
    protected $sortConfigBuilder;

    /**
     * @var \Spryker\Client\Search\Plugin\Config\PaginationConfigBuilderInterface
     */
    protected $paginationConfigBuilder;

    public function __construct()
    {
        $this->facetConfigBuilder = $this->getFactory()->createFacetConfigBuilder();
        $this->sortConfigBuilder = $this->getFactory()->createSortConfigBuilder();
        $this->paginationConfigBuilder = $this->getFactory()->createPaginationConfigBuilder();

        $searchConfigBuilder = $this->getFactory()->getSearchConfigBuilder();

        $searchConfigBuilder->buildFacetConfig($this->facetConfigBuilder);
        $searchConfigBuilder->buildSortConfig($this->sortConfigBuilder);
        $searchConfigBuilder->buildPaginationConfig($this->paginationConfigBuilder);

        $this->extendConfig();
    }

    /**
     * @return \Spryker\Client\Search\Plugin\Config\FacetConfigBuilderInterface
     */
    public function getFacetConfigBuilder()
    {
        return $this->facetConfigBuilder;
    }

    /**
     * @return \Spryker\Client\Search\Plugin\Config\SortConfigBuilderInterface
     */
    public function getSortConfigBuilder()
    {
        return $this->sortConfigBuilder;
    }

    /**
     * @return \Spryker\Client\Search\Plugin\Config\PaginationConfigBuilderInterface
     */
    public function getPaginationConfigBuilder()
    {
        return $this->paginationConfigBuilder;
    }

    /**
     * @return void
     */
    protected function extendConfig()
    {
        $configData = $this->loadDynamicConfigData();

        $this->setDynamicFacets($configData);
        $this->setDynamicSorts($configData);
    }

    /**
     * TODO: get real data from redis
     *
     * @return array
     */
    protected function loadDynamicConfigData()
    {
        return [
            'facets' => [
                
            ],
            'sorts' => [

            ],
        ];
    }

    /**
     * @param array $configData
     *
     * @return void
     */
    protected function setDynamicFacets(array $configData)
    {
        if (!isset($configData['facets'])) {
            return;
        }

        foreach ($configData['facets'] as $facetData) {
            $facetConfigTransfer = new FacetConfigTransfer();
            $facetConfigTransfer->fromArray($facetData, true);

            $this->facetConfigBuilder->addFacet($facetConfigTransfer);
        }
    }

    /**
     * @param array $configData
     *
     * @return void
     */
    protected function setDynamicSorts(array $configData)
    {
        if (!isset($configData['sorts'])) {
            return;
        }

        foreach ($configData['sorts'] as $sortData) {
            $sortConfigTransfer = new SortConfigTransfer();
            $sortConfigTransfer->fromArray($sortData, true);

            $this->sortConfigBuilder->addSort($sortConfigTransfer);
        }
    }

}
