<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchHttp\Config;

class SearchConfigBuilder implements SearchConfigBuilderInterface
{
    /**
     * @var \Spryker\Client\SearchHttp\Config\SearchConfigInterface
     */
    protected static SearchConfigInterface $searchConfig;

    /**
     * @var bool
     */
    protected static bool $isSearchConfigBuilt;

    /**
     * @var \Spryker\Client\SearchHttp\Config\FacetConfigInterface
     */
    protected FacetConfigInterface $facetConfig;

    /**
     * @var \Spryker\Client\SearchHttp\Config\SortConfigInterface
     */
    protected SortConfigInterface $sortConfig;

    /**
     * @var \Spryker\Client\SearchHttp\Config\PaginationConfigInterface
     */
    protected PaginationConfigInterface $paginationConfig;

    /**
     * @var array<\Spryker\Client\SearchExtension\Dependency\Plugin\SearchConfigBuilderPluginInterface>
     */
    protected array $searchConfigBuilderPlugins;

    /**
     * @var array<\Spryker\Client\SearchExtension\Dependency\Plugin\SearchConfigExpanderPluginInterface>
     */
    protected array $searchConfigExpanderPlugins = [];

    /**
     * @param \Spryker\Client\SearchHttp\Config\FacetConfigInterface $facetConfig
     * @param \Spryker\Client\SearchHttp\Config\SortConfigInterface $sortConfig
     * @param \Spryker\Client\SearchHttp\Config\PaginationConfigInterface $paginationConfig
     */
    public function __construct(
        FacetConfigInterface $facetConfig,
        SortConfigInterface $sortConfig,
        PaginationConfigInterface $paginationConfig
    ) {
        $this->facetConfig = $facetConfig;
        $this->sortConfig = $sortConfig;
        $this->paginationConfig = $paginationConfig;

        static::$isSearchConfigBuilt = false;
    }

    /**
     * @return \Spryker\Client\SearchHttp\Config\SearchConfigInterface
     */
    public function build(): SearchConfigInterface
    {
        if (!static::$isSearchConfigBuilt) {
            static::$searchConfig = new SearchConfig(
                $this->facetConfig,
                $this->sortConfig,
                $this->paginationConfig,
                $this->searchConfigBuilderPlugins,
                $this->searchConfigExpanderPlugins,
            );

            static::$isSearchConfigBuilt = true;
        }

        return static::$searchConfig;
    }

    /**
     * @param array<\Spryker\Client\SearchExtension\Dependency\Plugin\SearchConfigBuilderPluginInterface> $searchConfigBuilderPlugins
     *
     * @return void
     */
    public function setSearchConfigBuilderPlugins(array $searchConfigBuilderPlugins): void
    {
        $this->searchConfigBuilderPlugins = $searchConfigBuilderPlugins;
    }

    /**
     * @param array<\Spryker\Client\SearchExtension\Dependency\Plugin\SearchConfigExpanderPluginInterface> $searchConfigExpanderPlugins
     *
     * @return void
     */
    public function setSearchConfigExpanderPlugins(array $searchConfigExpanderPlugins): void
    {
        $this->searchConfigExpanderPlugins = $searchConfigExpanderPlugins;
    }
}
