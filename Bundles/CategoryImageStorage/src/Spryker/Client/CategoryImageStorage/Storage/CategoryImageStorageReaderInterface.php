<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CategoryImageStorage\Storage;

use Generated\Shared\Transfer\CategoryImageSetCollectionStorageTransfer;
use Generated\Shared\Transfer\CategoryImageStorageItemDataTransfer;

interface CategoryImageStorageReaderInterface
{
    /**
     * @deprecated Use `findCategoryImageStorageItemData()` instead.
     *
     * @param int $idCategory
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\CategoryImageSetCollectionStorageTransfer|null
     */
    public function findCategoryImageStorage(int $idCategory, string $localeName): ?CategoryImageSetCollectionStorageTransfer;

    /**
     * @param int $idCategory
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\CategoryImageStorageItemDataTransfer|null
     */
    public function findCategoryImageStorageItemData(int $idCategory, string $localeName): ?CategoryImageStorageItemDataTransfer;
}
