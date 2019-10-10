<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchElasticsearch\Config;

use Spryker\Client\SearchExtension\Config\FacetConfigInterface;
use Spryker\Client\SearchExtension\Config\PaginationConfigInterface;
use Spryker\Client\SearchExtension\Config\SortConfigInterface;

interface SearchConfigInterface
{
    /**
     * @return \Spryker\Client\SearchExtension\Config\FacetConfigInterface
     */
    public function getFacetConfig(): FacetConfigInterface;

    /**
     * @return \Spryker\Client\SearchExtension\Config\SortConfigInterface
     */
    public function getSortConfig(): SortConfigInterface;

    /**
     * @return \Spryker\Client\SearchExtension\Config\PaginationConfigInterface
     */
    public function getPaginationConfig(): PaginationConfigInterface;
}
