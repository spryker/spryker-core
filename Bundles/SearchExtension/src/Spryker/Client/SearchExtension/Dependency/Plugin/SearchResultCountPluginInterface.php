<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchExtension\Dependency\Plugin;

/**
 * Offers count capabilities for search results.
 */
interface SearchResultCountPluginInterface
{
    /**
     * Specification:
     * - Returns a count of the search results.
     * - Returns NULL if the supported count method cannot be applied to the search results.
     *
     * @api
     *
     * @param mixed $searchResult
     *
     * @return int|null
     */
    public function findTotalCount(mixed $searchResult): ?int;
}
