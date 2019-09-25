<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Dependency\Plugin;

/**
 * @deprecated Use `\Spryker\Client\SearchExtension\Config\FacetConfigInterface` instead.
 * @deprecated Use `\Spryker\Client\SearchExtension\Config\SortConfigInterface` instead.
 * @deprecated Use `\Spryker\Client\SearchExtension\Config\PaginationConfigInterface` instead.
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
