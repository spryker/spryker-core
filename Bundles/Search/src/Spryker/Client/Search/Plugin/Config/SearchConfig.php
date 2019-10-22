<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Plugin\Config;

use Generated\Shared\Transfer\SearchConfigExtensionTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\Search\Dependency\Plugin\SearchConfigInterface;

/**
 * @deprecated Use `\Spryker\Client\SearchElasticsearch\Config\SearchConfig` instead.
 *
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
    protected function extendFacetConfig(SearchConfigExtensionTransfer $searchConfigExtensionTransfer)
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
    protected function extendSortConfig(SearchConfigExtensionTransfer $searchConfigExtensionTransfer)
    {
        foreach ($searchConfigExtensionTransfer->getSortConfigs() as $sortConfigTransfer) {
            $this->sortConfigBuilder->addSort($sortConfigTransfer);
        }
    }
}
