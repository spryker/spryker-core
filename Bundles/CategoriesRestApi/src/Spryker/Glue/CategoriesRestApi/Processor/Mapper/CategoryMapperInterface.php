<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CategoriesRestApi\Processor\Mapper;

use Generated\Shared\Transfer\CategoryNodeStorageTransfer;
use Generated\Shared\Transfer\RestCategoriesTreeTransfer;
use Generated\Shared\Transfer\RestCategoryNodesAttributesTransfer;

interface CategoryMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\CategoryNodeStorageTransfer[] $categoryNodeStorageTransfers
     *
     * @return \Generated\Shared\Transfer\RestCategoriesTreeTransfer
     */
    public function mapCategoryTreeToRestCategoryTreesTransfer(array $categoryNodeStorageTransfers): RestCategoriesTreeTransfer;

    /**
     * @param \Generated\Shared\Transfer\CategoryNodeStorageTransfer $categoryNodeStorageTransfer
     *
     * @return \Generated\Shared\Transfer\RestCategoryNodesAttributesTransfer
     */
    public function mapCategoryNodeToRestCategoryNodesTransfer(CategoryNodeStorageTransfer $categoryNodeStorageTransfer): RestCategoryNodesAttributesTransfer;
}
