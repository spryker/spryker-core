<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchHttp\CountProvider;

interface SearchResultCountProviderInterface
{
    /**
     * @param mixed $searchResult
     *
     * @return int|null
     */
    public function findSearchResultTotalCount($searchResult): ?int;
}
