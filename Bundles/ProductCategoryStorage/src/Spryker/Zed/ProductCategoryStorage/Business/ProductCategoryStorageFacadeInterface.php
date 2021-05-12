<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryStorage\Business;

interface ProductCategoryStorageFacadeInterface
{
    /**
     * Specification:
     * - Queries all productCategories with the given productAbstractIds
     * - Stores data as json encoded to storage table
     * - Sends a copy of data to queue based on module config
     *
     * @api
     *
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    public function publish(array $productAbstractIds);

    /**
     * Specification:
     * - Finds and deletes productCategories storage entities with the given productAbstractIds
     * - Sends delete message to queue based on module config
     *
     * @api
     *
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    public function unpublish(array $productAbstractIds);

    /**
     * Specification:
     * - Returns related category ids with the given categoryIds
     *
     * @api
     *
     * @param int[] $categoryIds
     *
     * @return int[]
     */
    public function getRelatedCategoryIds(array $categoryIds);

    /**
     * Specification:
     * - Extracts category store IDs from the $eventTransfers created by category store events.
     * - Finds all category IDs related to category store IDs.
     * - Queries all product abstract IDs related to categories.
     * - Queries all productCategories with the given productAbstractIds.
     * - Stores data as json encoded to storage table.
     * - Sends a copy of data to queue based on module config.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     *
     * @return void
     */
    public function writeCollectionByCategoryStoreEvents(array $eventEntityTransfers): void;

    /**
     * Specification:
     * - Extracts category store IDs from the $eventTransfers created by category store events.
     * - Finds all category IDs related to category IDs.
     * - Queries all product abstract IDs related to categories.
     * - Queries all productCategories with the given productAbstractIds.
     * - Stores data as json encoded to storage table.
     * - Sends a copy of data to queue based on module config.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     *
     * @return void
     */
    public function writeCollectionByCategoryStorePublishingEvents(array $eventEntityTransfers): void;

    /**
     * Specification:
     * - Extracts category store IDs from the $eventTransfers created by category store events.
     * - Finds all category IDs related to category IDs.
     * - Queries all product abstract IDs related to categories.
     * - Deletes entities from `spy_product_abstract_category_storage` based on product abstract IDs.
     * - Sends a copy of data to queue based on module config.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     *
     * @return void
     */
    public function deleteCollectionByCategoryStoreEvents(array $eventEntityTransfers): void;
}
