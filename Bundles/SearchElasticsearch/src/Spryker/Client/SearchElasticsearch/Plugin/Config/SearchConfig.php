<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchElasticsearch\Plugin\Config;

use Generated\Shared\Transfer\SearchConfigExtensionTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\SearchExtension\Dependency\Plugin\FacetConfigBuilderInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\PaginationConfigBuilderInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\SearchConfigInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\SortConfigBuilderInterface;

/**
 * @method \Spryker\Client\SearchElasticsearch\SearchElasticsearchFactory getFactory()
 */
class SearchConfig extends AbstractPlugin implements SearchConfigInterface
{
    /**
     * @var \Spryker\Client\SearchExtension\Dependency\Plugin\FacetConfigBuilderInterface
     */
    protected $facetConfigBuilder;

    /**
     * @var \Spryker\Client\SearchExtension\Dependency\Plugin\SortConfigBuilderInterface
     */
    protected $sortConfigBuilder;

    /**
     * @var \Spryker\Client\SearchExtension\Dependency\Plugin\PaginationConfigBuilderInterface
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
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\FacetConfigBuilderInterface
     */
    public function getFacetConfigBuilder(): FacetConfigBuilderInterface
    {
        return $this->facetConfigBuilder;
    }

    /**
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\SortConfigBuilderInterface
     */
    public function getSortConfigBuilder(): SortConfigBuilderInterface
    {
        return $this->sortConfigBuilder;
    }

    /**
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\PaginationConfigBuilderInterface
     */
    public function getPaginationConfigBuilder(): PaginationConfigBuilderInterface
    {
        return $this->paginationConfigBuilder;
    }

    /**
     * @return void
     */
    protected function buildSearchConfig(): void
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
    protected function extendConfig(): void
    {
        $searchConfigExpanders = $this->getFactory()->getSearchConfigExpanderPlugins();
        foreach ($searchConfigExpanders as $searchConfigExpander) {
            $searchConfigExtensionTransfer = $searchConfigExpander->getSearchConfigExtension();

            $this->extendFacetConfig($searchConfigExtensionTransfer);
            $this->extendSortConfig($searchConfigExtensionTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\SearchConfigExtensionTransfer $searchConfigExtensionTransfer
     *
     * @return void
     */
    protected function extendFacetConfig(SearchConfigExtensionTransfer $searchConfigExtensionTransfer): void
    {
        foreach ($searchConfigExtensionTransfer->getFacetConfigs() as $facetConfigTransfer) {
            $this->facetConfigBuilder->addFacet($facetConfigTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\SearchConfigExtensionTransfer $searchConfigExtensionTransfer
     *
     * @return void
     */
    protected function extendSortConfig(SearchConfigExtensionTransfer $searchConfigExtensionTransfer): void
    {
        foreach ($searchConfigExtensionTransfer->getSortConfigs() as $sortConfigTransfer) {
            $this->sortConfigBuilder->addSort($sortConfigTransfer);
        }
    }
}
