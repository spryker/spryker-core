<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Catalog\Plugin\Config;

use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\SearchExtension\Config\FacetConfigInterface;
use Spryker\Client\SearchExtension\Config\PaginationConfigInterface;
use Spryker\Client\SearchExtension\Config\SortConfigInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\FacetConfigBuilderPluginInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\PaginationConfigBuilderPluginInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\SortConfigBuilderPluginInterface;

/**
 * @method \Spryker\Client\Catalog\CatalogFactory getFactory()
 */
class CatalogSearchConfigBuilderPlugin extends AbstractPlugin implements
    FacetConfigBuilderPluginInterface,
    SortConfigBuilderPluginInterface,
    PaginationConfigBuilderPluginInterface
{
    public const DEFAULT_ITEMS_PER_PAGE = 12;
    public const VALID_ITEMS_PER_PAGE_OPTIONS = [12, 24, 36];
    public const PARAMETER_NAME_PAGE = 'page';
    public const PARAMETER_NAME_ITEMS_PER_PAGE = 'ipp';

    /**
     * @param \Spryker\Client\SearchExtension\Config\FacetConfigInterface $facetConfigBuilder
     *
     * @return void
     */
    public function buildFacetConfig(FacetConfigInterface $facetConfigBuilder): void
    {
        $facetConfigBuilderPlugins = $this->getFactory()->getFacetConfigTransferBuilderPlugins();

        foreach ($facetConfigBuilderPlugins as $facetConfigBuilderPlugin) {
            $facetConfigBuilder->addFacet($facetConfigBuilderPlugin->build());
        }
    }

    /**
     * @param \Spryker\Client\SearchExtension\Config\SortConfigInterface $sortConfigBuilder
     *
     * @return void
     */
    public function buildSortConfig(SortConfigInterface $sortConfigBuilder): void
    {
        $sortConfigBuilderPlugins = $this->getFactory()->getSortConfigTransferBuilderPlugins();

        foreach ($sortConfigBuilderPlugins as $sortConfigBuilderPlugin) {
            $sortConfigBuilder->addSort($sortConfigBuilderPlugin->build());
        }
    }

    /**
     * @param \Spryker\Client\SearchExtension\Config\PaginationConfigInterface $paginationConfigBuilder
     *
     * @return void
     */
    public function buildPaginationConfig(PaginationConfigInterface $paginationConfigBuilder): void
    {
        $paginationConfigBuilder->setPagination(
            $this->getFactory()->getConfig()->getCatalogSearchPaginationConfigTransfer()
        );
    }
}
