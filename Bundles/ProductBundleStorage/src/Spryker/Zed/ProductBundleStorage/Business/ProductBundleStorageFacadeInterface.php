<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundleStorage\Business;

use Generated\Shared\Transfer\FilterTransfer;

interface ProductBundleStorageFacadeInterface
{
    /**
     * Specification:
     * - Publishes product_bundle data to storage based on product_bundle publish event.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByProductBundlePublishEvents(array $eventTransfers): void;

    /**
     * Specification:
     * - Publishes product_bundle data to storage based on product_bundle create, update, delete events.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByProductBundleEvents(array $eventTransfers): void;

    /**
     * Specification:
     * - Publishes product_bundle data to storage based on product events.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByProductEvents(array $eventTransfers): void;

    /**
     * Specification:
     * - Retrieves paginated product_bundle data from spy_product_bundle_storage table.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param int[] $productConcreteIds
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function getPaginatedProductBundleStorageDataTransfers(
        FilterTransfer $filterTransfer,
        array $productConcreteIds
    ): array;
}
