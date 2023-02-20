<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchHttp\Business;

use Generated\Shared\Transfer\SearchHttpConfigTransfer;

interface SearchHttpFacadeInterface
{
    /**
     * Specification:
     * - Publishes config for store.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SearchHttpConfigTransfer $searchHttpConfigTransfer
     * @param string $storeReference
     *
     * @return void
     */
    public function publishSearchHttpConfig(
        SearchHttpConfigTransfer $searchHttpConfigTransfer,
        string $storeReference
    ): void;

    /**
     * Specification:
     * - Removes config for store.
     *
     * @api
     *
     * @param string $storeReference
     * @param string $applicationId
     *
     * @return void
     */
    public function unpublishSearchHttpConfig(string $storeReference, string $applicationId): void;
}
