<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryStorage\Business;

interface CategoryStorageFacadeInterface
{
    /**
     * Specification:
     * - Queries all category nodes with categoryNodeIds
     * - Creates a data structure tree
     * - Stores data as json encoded to storage table
     * - Sends a copy of data to queue based on module config
     *
     * @api
     *
     * @param int[] $categoryNodeIds
     *
     * @return void
     */
    public function publish(array $categoryNodeIds): void;

    /**
     * Specification:
     * - Finds and deletes category node storage entities with categoryNodeIds
     * - Sends delete message to queue based on module config
     *
     * @api
     *
     * @param int[] $categoryNodeIds
     *
     * @return void
     */
    public function unpublish(array $categoryNodeIds): void;

    /**
     * Specification:
     * - Queries all categories
     * - Creates a data structure category tree
     * - Stores data as json encoded to storage table
     * - Sends a copy of data to queue based on module config
     *
     * @api
     *
     * @return void
     */
    public function publishCategoryTree(): void;

    /**
     * Specification:
     * - Finds and deletes all category tree storage entities
     * - Sends delete message to queue based on module config
     *
     * @api
     *
     * @return void
     */
    public function unpublishCategoryTree(): void;

    /**
     * Specification:
     * - Extracts category store IDs from the $eventTransfers created by category store events.
     * - Finds all category node IDs related to category store IDs.
     * - Queries all category nodes with category node IDs.
     * - Creates a data structure tree.
     * - Stores data as json encoded to storage table.
     * - Sends a copy of data to queue based on module config.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     *
     * @return void
     */
    public function writeCategoryNodeStorageCollectionByCategoryEvents(array $eventEntityTransfers): void;

    /**
     * Specification:
     * - Queries all categories.
     * - Creates a data structure category tree.
     * - Stores data as json encoded to storage table.
     * - Sends a copy of data to queue based on module config.
     *
     * @api
     *
     * @return void
     */
    public function writeCategoryTreeStorageCollection(): void;
}
