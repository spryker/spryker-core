<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Catalog\Plugin\Config;

use Generated\Shared\Transfer\PaginationConfigTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\Search\Dependency\Plugin\FacetConfigBuilderInterface;
use Spryker\Client\Search\Dependency\Plugin\PaginationConfigBuilderInterface;
use Spryker\Client\Search\Dependency\Plugin\SearchConfigBuilderInterface;
use Spryker\Client\Search\Dependency\Plugin\SortConfigBuilderInterface;

/**
 * @deprecated Use `\Spryker\Client\Catalog\Plugin\Config\FacetConfigPlugin` instead.
 * @deprecated Use `\Spryker\Client\Catalog\Plugin\Config\PaginationConfigPlugin` instead.
 * @deprecated Use `\Spryker\Client\Catalog\Plugin\Config\SortConfigPlugin` instead.
 *
 * @method \Spryker\Client\Catalog\CatalogFactory getFactory()
 */
class CatalogSearchConfigBuilder extends AbstractPlugin implements SearchConfigBuilderInterface
{
    public const DEFAULT_ITEMS_PER_PAGE = 12;
    public const VALID_ITEMS_PER_PAGE_OPTIONS = [12, 24, 36];
    public const PARAMETER_NAME_PAGE = 'page';
    public const PARAMETER_NAME_ITEMS_PER_PAGE = 'ipp';

    /**
     * @param \Spryker\Client\Search\Dependency\Plugin\FacetConfigBuilderInterface $facetConfigBuilder
     *
     * @return void
     */
    public function buildFacetConfig(FacetConfigBuilderInterface $facetConfigBuilder)
    {
        $facetConfigBuilderPlugins = $this->getFactory()->getFacetConfigTransferBuilderPlugins();

        foreach ($facetConfigBuilderPlugins as $facetConfigBuilderPlugin) {
            $facetConfigBuilder->addFacet($facetConfigBuilderPlugin->build());
        }
    }

    /**
     * @param \Spryker\Client\Search\Dependency\Plugin\SortConfigBuilderInterface $sortConfigBuilder
     *
     * @return void
     */
    public function buildSortConfig(SortConfigBuilderInterface $sortConfigBuilder)
    {
        $sortConfigBuilderPlugins = $this->getFactory()->getSortConfigTransferBuilderPlugins();

        foreach ($sortConfigBuilderPlugins as $sortConfigBuilderPlugin) {
            $sortConfigBuilder->addSort($sortConfigBuilderPlugin->build());
        }
    }

    /**
     * @param \Spryker\Client\Search\Dependency\Plugin\PaginationConfigBuilderInterface $paginationConfigBuilder
     *
     * @return void
     */
    public function buildPaginationConfig(PaginationConfigBuilderInterface $paginationConfigBuilder)
    {
        $paginationConfigTransfer = (new PaginationConfigTransfer())
            ->setParameterName(static::PARAMETER_NAME_PAGE)
            ->setItemsPerPageParameterName(static::PARAMETER_NAME_ITEMS_PER_PAGE)
            ->setDefaultItemsPerPage(static::DEFAULT_ITEMS_PER_PAGE)
            ->setValidItemsPerPageOptions(static::VALID_ITEMS_PER_PAGE_OPTIONS);

        $paginationConfigBuilder->setPagination($paginationConfigTransfer);
    }
}
