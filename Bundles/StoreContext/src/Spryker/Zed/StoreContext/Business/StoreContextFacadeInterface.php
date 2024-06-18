<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreContext\Business;

use Generated\Shared\Transfer\StoreCollectionTransfer;
use Generated\Shared\Transfer\StoreContextCollectionRequestTransfer;
use Generated\Shared\Transfer\StoreContextCollectionResponseTransfer;

interface StoreContextFacadeInterface
{
    /**
     * Specification:
     * - Requires `StoreCollectionTransfer.stores.idStore` to be set.
     * - Expands collection of store transfers with context data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StoreCollectionTransfer $storeCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\StoreCollectionTransfer
     */
    public function expandStoreCollection(StoreCollectionTransfer $storeCollectionTransfer): StoreCollectionTransfer;

    /**
     * Specification:
     * - Validates store context collection.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StoreContextCollectionRequestTransfer $storeContextCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\StoreContextCollectionResponseTransfer
     */
    public function validateStoreContextCollection(
        StoreContextCollectionRequestTransfer $storeContextCollectionRequestTransfer
    ): StoreContextCollectionResponseTransfer;

    /**
     * Specification:
     * - Requires `StoreCollectionTransfer.contexts.applicationContextCollections.store.idStore` to be set.
     * - Requires `StoreCollectionTransfer.contexts.applicationContexts.timezone` to be set.
     * - Requires `StoreCollectionTransfer.contexts.applicationContexts.application` to be set if not default value.
     * - Creates store context by store collection request transfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StoreContextCollectionRequestTransfer $storeCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\StoreContextCollectionResponseTransfer
     */
    public function createStoreContextCollection(StoreContextCollectionRequestTransfer $storeCollectionRequestTransfer): StoreContextCollectionResponseTransfer;

    /**
     * Specification:
     * - Requires `StoreCollectionTransfer.contexts.applicationContextCollections.store.idStore` to be set.
     * - Requires `StoreCollectionTransfer.contexts.applicationContexts.timezone` to be set.
     * - Requires `StoreCollectionTransfer.contexts.applicationContexts.application` to be set if not default value.
     * - Updates store context by store collection request transfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StoreContextCollectionRequestTransfer $storeCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\StoreContextCollectionResponseTransfer
     */
    public function updateStoreContextCollection(StoreContextCollectionRequestTransfer $storeCollectionRequestTransfer): StoreContextCollectionResponseTransfer;

    /**
     * Specification:
     * - Gets available timezones.
     *
     * @api
     *
     * @return array<string, string>
     */
    public function getAvilableTimeZones(): array;

    /**
     * Specification:
     * - Gets available application names.
     *
     * @api
     *
     * @return array<string>
     */
    public function getAvilableApplications(): array;
}
