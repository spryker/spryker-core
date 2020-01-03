<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImageStorage\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\CategoryImageStorageTransfer;
use Generated\Shared\Transfer\SpyCategoryImageStorageEntityTransfer;

interface CategoryImageStorageMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\SpyCategoryImageStorageEntityTransfer $categoryImageStorageEntityTransfer
     * @param \Generated\Shared\Transfer\CategoryImageStorageTransfer $categoryImageStorageTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryImageStorageTransfer
     */
    public function mapCategoryImageStorageEntityToTransfer(
        SpyCategoryImageStorageEntityTransfer $categoryImageStorageEntityTransfer,
        CategoryImageStorageTransfer $categoryImageStorageTransfer
    ): CategoryImageStorageTransfer;
}
