<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchHttp\CountProvider;

class SearchResultCountProvider implements SearchResultCountProviderInterface
{
    /**
     * @param mixed $searchResult
     *
     * @return int|null
     */
    public function findSearchResultTotalCount($searchResult): ?int
    {
        if (!is_array($searchResult)) {
            return null;
        }

        return $searchResult['pagination']['num_found'] ?? null;
    }
}
