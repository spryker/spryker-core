<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchElasticsearch\Config;

use Generated\Shared\Transfer\SearchConfigExtensionTransfer;
use Generated\Shared\Transfer\SearchConfigurationTransfer;
use Spryker\Client\SearchExtension\Dependency\Plugin\SearchConfigBuilderPluginInterface;

class SearchConfig implements SearchConfigInterface
{
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
     * @var \Spryker\Client\SearchExtension\Dependency\Plugin\SearchConfigExpanderPluginInterface[]
     */
    protected $configExpanderPlugins;

    /**
     * @param \Spryker\Client\SearchElasticsearch\Config\FacetConfigInterface $facetConfig
     * @param \Spryker\Client\SearchElasticsearch\Config\SortConfigInterface $sortConfig
     * @param \Spryker\Client\SearchElasticsearch\Config\PaginationConfigInterface $paginationConfig
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\SearchConfigBuilderPluginInterface|null $configBuilderPlugin
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\SearchConfigExpanderPluginInterface[] $configExpanderPlugins
     */
    public function __construct(
        FacetConfigInterface $facetConfig,
        SortConfigInterface $sortConfig,
        PaginationConfigInterface $paginationConfig,
        ?SearchConfigBuilderPluginInterface $configBuilderPlugin = null,
        array $configExpanderPlugins = []
    ) {
        $this->facetConfig = $facetConfig;
        $this->sortConfig = $sortConfig;
        $this->paginationConfig = $paginationConfig;
        $this->configExpanderPlugins = $configExpanderPlugins;

        $this->buildSearchConfig($configBuilderPlugin);
        $this->expandSearchConfig($configExpanderPlugins);
    }

    /**
     * @return \Spryker\Client\SearchElasticsearch\Config\FacetConfigInterface
     */
    public function getFacetConfig(): FacetConfigInterface
    {
        return $this->facetConfig;
    }

    /**
     * @return \Spryker\Client\SearchElasticsearch\Config\SortConfigInterface
     */
    public function getSortConfig(): SortConfigInterface
    {
        return $this->sortConfig;
    }

    /**
     * @return \Spryker\Client\SearchElasticsearch\Config\PaginationConfigInterface
     */
    public function getPaginationConfig(): PaginationConfigInterface
    {
        return $this->paginationConfig;
    }

    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\SearchConfigBuilderPluginInterface|null $configBuilderPlugin
     *
     * @return void
     */
    protected function buildSearchConfig(?SearchConfigBuilderPluginInterface $configBuilderPlugin): void
    {
        if (!$configBuilderPlugin) {
            return;
        }

        $searchConfigurationTransfer = $configBuilderPlugin->buildConfig(new SearchConfigurationTransfer());

        foreach ($searchConfigurationTransfer->getFacetConfigItems() as $facetConfigTransfer) {
            $this->facetConfig->addFacet($facetConfigTransfer);
        }

        foreach ($searchConfigurationTransfer->getSortConfigItems() as $sortConfigTransfer) {
            $this->sortConfig->addSort($sortConfigTransfer);
        }

        $this->paginationConfig->setPagination(
            $searchConfigurationTransfer->getPaginationConfig()
        );
    }

    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\SearchConfigExpanderPluginInterface[] $configExpanderPlugins
     *
     * @return void
     */
    protected function expandSearchConfig(array $configExpanderPlugins): void
    {
        foreach ($configExpanderPlugins as $configExpanderPlugin) {
            $searchConfigExtensionTransfer = $configExpanderPlugin->getSearchConfigExtension();

            $this->extendFacetConfig($searchConfigExtensionTransfer);
            $this->extendSortConfig($searchConfigExtensionTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\SearchConfigExtensionTransfer $searchConfigExtensionTransfer
     *
     * @return void
     */
    protected function extendFacetConfig(SearchConfigExtensionTransfer $searchConfigExtensionTransfer)
    {
        foreach ($searchConfigExtensionTransfer->getFacetConfigs() as $facetConfigTransfer) {
            $this->facetConfig->addFacet($facetConfigTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\SearchConfigExtensionTransfer $searchConfigExtensionTransfer
     *
     * @return void
     */
    protected function extendSortConfig(SearchConfigExtensionTransfer $searchConfigExtensionTransfer)
    {
        foreach ($searchConfigExtensionTransfer->getSortConfigs() as $sortConfigTransfer) {
            $this->sortConfig->addSort($sortConfigTransfer);
        }
    }
}
