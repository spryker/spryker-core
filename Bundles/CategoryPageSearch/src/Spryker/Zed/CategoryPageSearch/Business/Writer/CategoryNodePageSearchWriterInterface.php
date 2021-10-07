<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryPageSearch\Business\Writer;

use Generated\Shared\Transfer\CategoryNodeCriteriaTransfer;

interface CategoryNodePageSearchWriterInterface
{
    /**
     * @param array<int> $categoryNodeIds
     *
     * @return void
     */
    public function writeCategoryNodePageSearchCollection(array $categoryNodeIds): void;

    /**
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeCategoryNodePageSearchCollectionByCategoryNodeEvents(array $eventEntityTransfers): void;

    /**
     * @param \Generated\Shared\Transfer\CategoryNodeCriteriaTransfer $categoryNodeCriteriaTransfer
     *
     * @return void
     */
    public function writeCategoryNodeStorageCollectionByCategoryNodeCriteria(CategoryNodeCriteriaTransfer $categoryNodeCriteriaTransfer): void;
}
