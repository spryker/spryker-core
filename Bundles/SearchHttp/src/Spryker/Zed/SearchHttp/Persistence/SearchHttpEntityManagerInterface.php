<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchHttp\Persistence;

use Generated\Shared\Transfer\SearchHttpConfigTransfer;
use Generated\Shared\Transfer\StoreTransfer;

interface SearchHttpEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\SearchHttpConfigTransfer $searchHttpConfigTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return void
     */
    public function saveSearchHttpConfig(
        SearchHttpConfigTransfer $searchHttpConfigTransfer,
        StoreTransfer $storeTransfer
    ): void;

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param string $applicationId
     *
     * @return void
     */
    public function deleteSearchHttpConfig(StoreTransfer $storeTransfer, string $applicationId): void;
}
