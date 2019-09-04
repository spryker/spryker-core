<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchExtension\Dependency\Plugin;

interface SearchConfigBuilderInterface
{
    /**
     * @api
     *
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\FacetConfigBuilderInterface $facetConfigBuilder
     *
     * @return void
     */
    public function buildFacetConfig(FacetConfigBuilderInterface $facetConfigBuilder);

    /**
     * @api
     *
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\SortConfigBuilderInterface $sortConfigBuilder
     *
     * @return void
     */
    public function buildSortConfig(SortConfigBuilderInterface $sortConfigBuilder);

    /**
     * @api
     *
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\PaginationConfigBuilderInterface $paginationConfigBuilder
     *
     * @return void
     */
    public function buildPaginationConfig(PaginationConfigBuilderInterface $paginationConfigBuilder);
}

class_alias(SearchConfigBuilderInterface::class, 'Spryker\Client\Search\Dependency\Plugin\SearchConfigBuilderInterface', false);
