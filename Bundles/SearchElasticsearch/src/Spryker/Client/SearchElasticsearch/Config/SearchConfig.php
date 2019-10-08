<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchElasticsearch\Config;

use Generated\Shared\Transfer\SearchConfigExtensionTransfer;
use Spryker\Client\SearchExtension\Config\FacetConfigInterface;
use Spryker\Client\SearchExtension\Config\PaginationConfigInterface;
use Spryker\Client\SearchExtension\Config\SortConfigInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\FacetConfigBuilderPluginInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\PaginationConfigBuilderPluginInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\SearchConfigBuilderPluginInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\SortConfigBuilderPluginInterface;

class SearchConfig implements SearchConfigInterface
{
    /**
     * @var \Spryker\Client\SearchExtension\Config\FacetConfigInterface
     */
    protected $facetConfig;

    /**
     * @var \Spryker\Client\SearchExtension\Config\SortConfigInterface
     */
    protected $sortConfig;

    /**
     * @var \Spryker\Client\SearchExtension\Config\PaginationConfigInterface
     */
    protected $paginationConfig;

    /**
     * @var \Spryker\Client\SearchExtension\Dependency\Plugin\SearchConfigExpanderPluginInterface[]
     */
    protected $configExpanderPlugins;

    /**
     * @param \Spryker\Client\SearchExtension\Config\FacetConfigInterface $facetConfig
     * @param \Spryker\Client\SearchExtension\Config\SortConfigInterface $sortConfig
     * @param \Spryker\Client\SearchExtension\Config\PaginationConfigInterface $paginationConfig
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
     * @return \Spryker\Client\SearchExtension\Config\FacetConfigInterface
     */
    public function getFacetConfig(): FacetConfigInterface
    {
        return $this->facetConfig;
    }

    /**
     * @return \Spryker\Client\SearchExtension\Config\SortConfigInterface
     */
    public function getSortConfig(): SortConfigInterface
    {
        return $this->sortConfig;
    }

    /**
     * @return \Spryker\Client\SearchExtension\Config\PaginationConfigInterface
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

        if ($configBuilderPlugin instanceof FacetConfigBuilderPluginInterface) {
            $configBuilderPlugin->buildFacetConfig($this->getFacetConfig());
        }

        if ($configBuilderPlugin instanceof SortConfigBuilderPluginInterface) {
            $configBuilderPlugin->buildSortConfig($this->getSortConfig());
        }

        if ($configBuilderPlugin instanceof PaginationConfigBuilderPluginInterface) {
            $configBuilderPlugin->buildPaginationConfig($this->getPaginationConfig());
        }
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
