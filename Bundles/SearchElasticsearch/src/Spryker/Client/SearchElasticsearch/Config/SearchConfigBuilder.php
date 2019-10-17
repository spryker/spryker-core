<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchElasticsearch\Config;

class SearchConfigBuilder implements SearchConfigBuilderInterface
{
    /**
     * @var \Spryker\Client\SearchElasticsearch\Config\SearchConfigInterface
     */
    protected static $searchConfig;

    /**
     * @var \Spryker\Client\SearchElasticsearch\Config\FacetConfigInterface
     */
    protected $facetConfig;

    /**
     * @var \Spryker\Client\SearchElasticsearch\Config\SortConfigInterface
     */
    protected $sortConfig;

    /**
     * @var \Spryker\Client\SearchElasticsearch\Config\PaginationConfigInterface
     */
    protected $paginationConfig;

    /**
     * @var \Spryker\Client\SearchExtension\Dependency\Plugin\SearchConfigBuilderPluginInterface[]
     */
    protected $searchConfigBuilderPlugins;

    /**
     * @var \Spryker\Client\SearchExtension\Dependency\Plugin\SearchConfigExpanderPluginInterface[]
     */
    protected $searchConfigExpanderPlugins = [];

    /**
     * @param \Spryker\Client\SearchElasticsearch\Config\FacetConfigInterface $facetConfig
     * @param \Spryker\Client\SearchElasticsearch\Config\SortConfigInterface $sortConfig
     * @param \Spryker\Client\SearchElasticsearch\Config\PaginationConfigInterface $paginationConfig
     */
    public function __construct(
        FacetConfigInterface $facetConfig,
        SortConfigInterface $sortConfig,
        PaginationConfigInterface $paginationConfig
    ) {
        $this->facetConfig = $facetConfig;
        $this->sortConfig = $sortConfig;
        $this->paginationConfig = $paginationConfig;
    }

    /**
     * @return \Spryker\Client\SearchElasticsearch\Config\SearchConfigInterface
     */
    public function build(): SearchConfigInterface
    {
        if (!static::$searchConfig) {
            static::$searchConfig = new SearchConfig(
                $this->facetConfig,
                $this->sortConfig,
                $this->paginationConfig,
                $this->searchConfigBuilderPlugins,
                $this->searchConfigExpanderPlugins
            );
        }

        return static::$searchConfig;
    }

    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\SearchConfigBuilderPluginInterface[] $searchConfigBuilderPlugins
     *
     * @return void
     */
    public function setSearchConfigBuilderPlugins(array $searchConfigBuilderPlugins): void
    {
        $this->searchConfigBuilderPlugins = $searchConfigBuilderPlugins;
    }

    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\SearchConfigExpanderPluginInterface[] $searchConfigExpanderPlugins
     *
     * @return void
     */
    public function setSearchConfigExpanderPlugins(array $searchConfigExpanderPlugins): void
    {
        $this->searchConfigExpanderPlugins = $searchConfigExpanderPlugins;
    }
}
