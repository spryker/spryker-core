<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Dependency\Plugin;

/**
 * @deprecated Use `\Spryker\Client\SearchExtension\Config\FacetConfigBuilderInterface` instead.
 * @deprecated Use `\Spryker\Client\SearchExtension\Config\SortConfigBuilderInterface` instead.
 * @deprecated Use `\Spryker\Client\SearchExtension\Config\PaginationConfigBuilderInterface` instead.
 */
interface SearchConfigInterface
{
    /**
     * @api
     *
     * @return \Spryker\Client\Search\Dependency\Plugin\FacetConfigBuilderInterface
     */
    public function getFacetConfigBuilder();

    /**
     * @api
     *
     * @return \Spryker\Client\Search\Dependency\Plugin\SortConfigBuilderInterface
     */
    public function getSortConfigBuilder();

    /**
     * @api
     *
     * @return \Spryker\Client\Search\Dependency\Plugin\PaginationConfigBuilderInterface
     */
    public function getPaginationConfigBuilder();
}
