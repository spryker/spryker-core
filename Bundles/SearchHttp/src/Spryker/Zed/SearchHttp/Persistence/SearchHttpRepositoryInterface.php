<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchHttp\Persistence;

use Generated\Shared\Transfer\SearchHttpConfigCriteriaTransfer;
use Propel\Runtime\Collection\ObjectCollection;

interface SearchHttpRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\SearchHttpConfigCriteriaTransfer $searchHttpConfigCriteriaTransfer
     *
     * @return \Propel\Runtime\Collection\ObjectCollection
     */
    public function getFilteredSearchHttpEntityTransfers(
        SearchHttpConfigCriteriaTransfer $searchHttpConfigCriteriaTransfer
    ): ObjectCollection;
}
