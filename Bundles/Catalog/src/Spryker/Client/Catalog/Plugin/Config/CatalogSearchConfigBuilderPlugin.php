<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Catalog\src\Spryker\Client\Catalog\Plugin\Config;

use Generated\Shared\Transfer\PaginationConfigTransfer;
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
        $paginationConfigTransfer = (new PaginationConfigTransfer())
            ->setParameterName(static::PARAMETER_NAME_PAGE)
            ->setItemsPerPageParameterName(static::PARAMETER_NAME_ITEMS_PER_PAGE)
            ->setDefaultItemsPerPage(static::DEFAULT_ITEMS_PER_PAGE)
            ->setValidItemsPerPageOptions(static::VALID_ITEMS_PER_PAGE_OPTIONS);

        $paginationConfigBuilder->setPagination($paginationConfigTransfer);
    }
}
