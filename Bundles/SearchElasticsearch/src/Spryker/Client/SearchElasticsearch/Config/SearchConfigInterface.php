<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchElasticsearch\Config;

interface SearchConfigInterface
{
    /**
     * @return \Spryker\Client\SearchElasticsearch\Config\FacetConfigInterface
     */
    public function getFacetConfig(): FacetConfigInterface;

    /**
     * @return \Spryker\Client\SearchElasticsearch\Config\SortConfigInterface
     */
    public function getSortConfig(): SortConfigInterface;

    /**
     * @return \Spryker\Client\SearchElasticsearch\Config\PaginationConfigInterface
     */
    public function getPaginationConfig(): PaginationConfigInterface;
}
