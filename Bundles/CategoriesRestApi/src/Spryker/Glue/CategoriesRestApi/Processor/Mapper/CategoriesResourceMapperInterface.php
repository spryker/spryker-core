<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CategoriesRestApi\Processor\Mapper;

use Generated\Shared\Transfer\ProductAbstractCategoryStorageTransfer;
use Generated\Shared\Transfer\RestCategoriesTreeTransfer;
use Generated\Shared\Transfer\RestProductCategoriesTreeTransfer;

interface CategoriesResourceMapperInterface
{
    /**
     * @param array $categoriesResource
     *
     * @return \Generated\Shared\Transfer\RestCategoriesTreeTransfer
     */
    public function mapCategoriesResourceToRestCategoriesTransfer(array $categoriesResource): RestCategoriesTreeTransfer;

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractCategoryStorageTransfer $productAbstractCategoryStorageTransfer
     *
     * @return \Generated\Shared\Transfer\RestProductCategoriesTreeTransfer
     */
    public function mapProductCategoriesToRestProductCategoriesTransfer(ProductAbstractCategoryStorageTransfer $productAbstractCategoryStorageTransfer): RestProductCategoriesTreeTransfer;
}