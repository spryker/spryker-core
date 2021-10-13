<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Updater;

use Generated\Shared\Transfer\UpdateCategoryStoreRelationRequestTransfer;

interface CategoryStoreUpdaterInterface
{
    /**
     * @param \Generated\Shared\Transfer\UpdateCategoryStoreRelationRequestTransfer $updateCategoryStoreRelationRequestTransfer
     *
     * @return void
     */
    public function updateCategoryStoreRelationWithMainChildrenPropagation(
        UpdateCategoryStoreRelationRequestTransfer $updateCategoryStoreRelationRequestTransfer
    ): void;
}
