<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchExtension\Dependency\Plugin;

interface SearchConfigInterface
{
    /**
     * @api
     *
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\FacetConfigBuilderInterface
     */
    public function getFacetConfigBuilder();

    /**
     * @api
     *
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\SortConfigBuilderInterface
     */
    public function getSortConfigBuilder();

    /**
     * @api
     *
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\PaginationConfigBuilderInterface
     */
    public function getPaginationConfigBuilder();
}
