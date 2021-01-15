<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryExtension\Dependency\Plugin;

use Generated\Shared\Transfer\StoreRelationTransfer;

interface CategoryStoreAssignerPluginInterface
{
    /**
     * Specification:
     * - Updates category store relation of the category and all its child categories.
     *
     * @api
     *
     * @param int $idCategory
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $newStoreAssignment
     * @param \Generated\Shared\Transfer\StoreRelationTransfer|null $currentStoreAssignment
     *
     * @return void
     */
    public function handleStoreRelationUpdate(
        int $idCategory,
        StoreRelationTransfer $newStoreAssignment,
        ?StoreRelationTransfer $currentStoreAssignment = null
    ): void;
}
