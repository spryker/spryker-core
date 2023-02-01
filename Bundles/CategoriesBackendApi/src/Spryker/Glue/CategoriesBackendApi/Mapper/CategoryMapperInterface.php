<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CategoriesBackendApi\Mapper;

use Generated\Shared\Transfer\ApiCategoryAttributesTransfer;
use Generated\Shared\Transfer\CategoryTransfer;

interface CategoryMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ApiCategoryAttributesTransfer $apiCategoryAttributesTransfer
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    public function mapApiCategoryAttributesTransferToCategoryTransfer(
        ApiCategoryAttributesTransfer $apiCategoryAttributesTransfer,
        CategoryTransfer $categoryTransfer
    ): CategoryTransfer;
}
