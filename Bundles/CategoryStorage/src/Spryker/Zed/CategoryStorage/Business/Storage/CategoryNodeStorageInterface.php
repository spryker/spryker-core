<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryStorage\Business\Storage;

interface CategoryNodeStorageInterface
{
    /**
     * @param int[] $categoryNodeIds
     *
     * @return void
     */
    public function publish(array $categoryNodeIds): void;

    /**
     * @param int[] $categoryNodeIds
     *
     * @return void
     */
    public function unpublish(array $categoryNodeIds): void;

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     *
     * @return void
     */
    public function writeCategoryNodeStorageCollectionByCategoryEvents(array $eventEntityTransfers): void;
}
