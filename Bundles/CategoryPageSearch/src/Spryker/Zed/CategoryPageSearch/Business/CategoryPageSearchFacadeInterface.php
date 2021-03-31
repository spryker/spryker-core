<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryPageSearch\Business;

interface CategoryPageSearchFacadeInterface
{
    /**
     * Specification:
     * - Queries all category nodes with these ids
     * - Creates a data structure tree
     * - Stores data as json encoded to search table
     * - Sends a copy of data to the queue.
     *
     * @api
     *
     * @deprecated This will be deleted in the next major without replacement.
     *
     * @param array $categoryNodeIds
     *
     * @return void
     */
    public function publish(array $categoryNodeIds);

    /**
     * Specification:
     * - Finds and deletes category node search entities based on these ids
     * - Sends a copy of data to the queue.
     *
     * @api
     *
     * @deprecated This will be deleted in the next major without replacement.
     *
     * @param array $categoryNodeIds
     *
     * @return void
     */
    public function unpublish(array $categoryNodeIds);

    /**
     * Specification:
     * - Extracts category store IDs from the $eventTransfers created by category store entity events.
     * - Finds all category node IDs related to category store IDs.
     * - Queries all category nodes with these ids.
     * - Creates a data structure tree.
     * - Stores data as json encoded to search table.
     * - Sends a copy of data to the queue.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     *
     * @return void
     */
    public function writeCategoryNodePageSearchCollectionByCategoryStoreEvents(array $eventEntityTransfers): void;

    /**
     * Specification:
     * - Extracts category store IDs from the $eventTransfers created by category store publish events.
     * - Finds all category node IDs related to category store IDs.
     * - Queries all category nodes with these ids.
     * - Creates a data structure tree.
     * - Stores data as json encoded to search table.
     * - Sends a copy of data to the queue.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     *
     * @return void
     */
    public function writeCategoryNodePageSearchCollectionByCategoryStorePublishEvents(array $eventEntityTransfers): void;

    /**
     * Specification:
     * - Extracts category IDs from the $eventTransfers created by category attribute entity events.
     * - Finds all category node IDs related to category IDs.
     * - Creates a data structure tree.
     * - Stores data as json encoded to search table.
     * - Sends a copy of data to the queue.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     *
     * @return void
     */
    public function writeCategoryNodePageSearchCollectionByCategoryAttributeEvents(array $eventEntityTransfers): void;

    /**
     * Specification:
     * - Extracts category IDs from the $eventTransfers created by category entity events.
     * - Finds all category node IDs related to category IDs.
     * - Creates a data structure tree.
     * - Stores data as json encoded to search table.
     * - Sends a copy of data to the queue.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     *
     * @return void
     */
    public function writeCategoryNodePageSearchCollectionByCategoryEvents(array $eventEntityTransfers): void;

    /**
     * Specification:
     * - Extracts category template IDs from the $eventTransfers created by category template events.
     * - Finds all category node IDs related to category template IDs.
     * - Creates a data structure tree.
     * - Stores data as json encoded to search table.
     * - Sends a copy of data to the queue.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     *
     * @return void
     */
    public function writeCategoryNodePageSearchCollectionByCategoryTemplateEvents(array $eventEntityTransfers): void;

    /**
     * Specification:
     * - Extracts category node IDs from the $eventTransfers created by category node events.
     * - Creates a data structure tree.
     * - Stores data as json encoded to search table.
     * - Sends a copy of data to the queue.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     *
     * @return void
     */
    public function writeCategoryNodePageSearchCollectionByCategoryNodeEvents(array $eventEntityTransfers): void;

    /**
     * Specification:
     * - Extracts category IDs from the $eventTransfers created by category attribute entity events.
     * - Finds all category node IDs related to category IDs.
     * - Deletes category node storage entities with category node IDs.
     * - Sends a copy of data to the queue.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     *
     * @return void
     */
    public function deleteCategoryNodePageSearchCollectionByCategoryAttributeEvents(array $eventEntityTransfers): void;

    /**
     * Specification:
     * - Extracts category IDs from the $eventTransfers created by category entity events.
     * - Finds all category node IDs related to category IDs.
     * - Deletes category node storage entities with category node IDs.
     * - Sends a copy of data to the queue.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     *
     * @return void
     */
    public function deleteCategoryNodePageSearchCollectionByCategoryEvents(array $eventEntityTransfers): void;

    /**
     * Specification:
     * - Extracts category template IDs from the $eventTransfers created by category template events.
     * - Finds all category node IDs related to category template IDs.
     * - Deletes category node storage entities with category node IDs.
     * - Sends a copy of data to the queue.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     *
     * @return void
     */
    public function deleteCategoryNodePageSearchCollectionByCategoryTemplateEvents(array $eventEntityTransfers): void;

    /**
     * Specification:
     * - Extracts category node IDs from the $eventTransfers created by category node events.
     * - Deletes category node storage entities with category node IDs.
     * - Sends a copy of data to the queue.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     *
     * @return void
     */
    public function deleteCategoryNodePageSearchCollectionByCategoryNodeEvents(array $eventEntityTransfers): void;

    /**
     * Specification:
     * - Retrieves a collection of synchronization data according to provided offset, limit and categoryNodeIds.
     *
     * @api
     *
     * @param int $offset
     * @param int $limit
     * @param int[] $categoryNodeIds
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function findSynchronizationDataTransfersByIds(int $offset, int $limit, array $categoryNodeIds): array;
}
