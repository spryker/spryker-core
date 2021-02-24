<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryExtension\Dependency\Plugin;

use Generated\Shared\Transfer\UpdateCategoryStoreRelationRequestTransfer;

/**
 * Use this plugin interface to handle updating of the category store relations.
 */
interface CategoryStoreAssignerPluginInterface
{
    /**
     * Specification:
     * - Updates category store relation of the category and all its child categories.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UpdateCategoryStoreRelationRequestTransfer $updateCategoryStoreRelationRequestTransfer
     *
     * @return void
     */
    public function handleStoreRelationUpdate(UpdateCategoryStoreRelationRequestTransfer $updateCategoryStoreRelationRequestTransfer): void;
}
