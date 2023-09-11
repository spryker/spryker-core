<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchHttp\Config;

interface SearchConfigInterface
{
    /**
     * @return \Spryker\Client\SearchHttp\Config\FacetConfigInterface
     */
    public function getFacetConfig(): FacetConfigInterface;

    /**
     * @return \Spryker\Client\SearchHttp\Config\SortConfigInterface
     */
    public function getSortConfig(): SortConfigInterface;

    /**
     * @return \Spryker\Client\SearchHttp\Config\PaginationConfigInterface
     */
    public function getPaginationConfig(): PaginationConfigInterface;
}
