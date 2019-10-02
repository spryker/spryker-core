<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Dependency\Plugin;

/**
 * @deprecated Use `Spryker\Client\SearchExtension\Dependency\Plugin\FacetConfigPluginInterface` instead.
 * @deprecated Use `Spryker\Client\SearchExtension\Dependency\Plugin\SortConfigPluginInterface` instead.
 * @deprecated Use `Spryker\Client\SearchExtension\Dependency\Plugin\PaginationConfigPluginInterface` instead.
 */
interface SearchConfigBuilderInterface
{
    /**
     * @api
     *
     * @param \Spryker\Client\Search\Dependency\Plugin\FacetConfigBuilderInterface $facetConfigBuilder
     *
     * @return void
     */
    public function buildFacetConfig(FacetConfigBuilderInterface $facetConfigBuilder);

    /**
     * @api
     *
     * @param \Spryker\Client\Search\Dependency\Plugin\SortConfigBuilderInterface $sortConfigBuilder
     *
     * @return void
     */
    public function buildSortConfig(SortConfigBuilderInterface $sortConfigBuilder);

    /**
     * @api
     *
     * @param \Spryker\Client\Search\Dependency\Plugin\PaginationConfigBuilderInterface $paginationConfigBuilder
     *
     * @return void
     */
    public function buildPaginationConfig(PaginationConfigBuilderInterface $paginationConfigBuilder);
}
