<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
     * @deprecated Use {@link \Spryker\Zed\SearchHttp\Business\SearchHttpFacadeInterface::saveSearchHttpConfig()} instead.
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
     * @deprecated Use {@link \Spryker\Zed\SearchHttp\Business\SearchHttpFacadeInterface::deleteSearchHttpConfig()} instead.
     *
     * @param string $storeReference
     * @param string $applicationId
     *
     * @return void
     */
    public function unpublishSearchHttpConfig(string $storeReference, string $applicationId): void;

    /**
     * Specification:
     * - Iterates through all the stores to save the search http config using the data from given `SearchHttpConfig` transfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SearchHttpConfigTransfer $searchHttpConfigTransfer
     *
     * @return void
     */
    public function saveSearchHttpConfig(SearchHttpConfigTransfer $searchHttpConfigTransfer): void;

    /**
     * Specification:
     * - Iterates through all the stores to delete the search http config using the data from given `SearchHttpConfig` transfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SearchHttpConfigTransfer $searchHttpConfigTransfer
     *
     * @return void
     */
    public function deleteSearchHttpConfig(SearchHttpConfigTransfer $searchHttpConfigTransfer): void;
}
