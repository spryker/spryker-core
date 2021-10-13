<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryStorage\Business\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\CategoryNodeStorageTransfer;

interface CategoryLocalizedAttributesMapperInterface
{
    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer> $categoryLocalizedAttributesTransfers
     * @param \Generated\Shared\Transfer\CategoryNodeStorageTransfer $categoryNodeStorageTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\CategoryNodeStorageTransfer
     */
    public function mapCategoryLocalizedAttributesTransfersToCategoryNodeStorageTransferForLocale(
        ArrayObject $categoryLocalizedAttributesTransfers,
        CategoryNodeStorageTransfer $categoryNodeStorageTransfer,
        string $localeName
    ): CategoryNodeStorageTransfer;
}
