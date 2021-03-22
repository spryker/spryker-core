<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryStorage\Business\Deleter;

interface CategoryNodeStorageDeleterInterface
{
    /**
     * @param \Generated\Shared\Transfer\CategoryNodeStorageTransfer[][][] $categoryNodeStorageTransferTreesIndexedByLocaleAndStore
     * @param int[] $categoryNodeIds
     *
     * @return void
     */
    public function deleteMissingCategoryNodeStorage(array $categoryNodeStorageTransferTreesIndexedByLocaleAndStore, array $categoryNodeIds): void;

    /**
     * @param int[] $categoryNodeIds
     *
     * @return void
     */
    public function deleteCategoryNodeStorageCollection(array $categoryNodeIds): void;

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     *
     * @return void
     */
    public function deleteCategoryNodeStorageCollectionByCategoryAttributeEvents(array $eventEntityTransfers): void;

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     *
     * @return void
     */
    public function deleteCategoryNodeStorageCollectionByCategoryEvents(array $eventEntityTransfers): void;

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     *
     * @return void
     */
    public function deleteCategoryNodeStorageCollectionByCategoryTemplateEvents(array $eventEntityTransfers): void;

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     *
     * @return void
     */
    public function deleteCategoryNodeStorageCollectionByCategoryNodeEvents(array $eventEntityTransfers): void;
}
