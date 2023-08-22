<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CategoriesBackendApi\Mapper;

use Generated\Shared\Transfer\CategoriesBackendApiAttributesTransfer;
use Generated\Shared\Transfer\CategoryTransfer;

interface CategoryMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\CategoriesBackendApiAttributesTransfer $categoriesBackendApiAttributesTransfer
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    public function mapCategoriesBackendApiAttributesTransferToCategoryTransfer(
        CategoriesBackendApiAttributesTransfer $categoriesBackendApiAttributesTransfer,
        CategoryTransfer $categoryTransfer
    ): CategoryTransfer;
}
