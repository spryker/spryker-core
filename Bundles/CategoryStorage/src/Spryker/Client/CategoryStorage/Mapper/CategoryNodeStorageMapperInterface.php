<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CategoryStorage\Mapper;

use ArrayObject;

interface CategoryNodeStorageMapperInterface
{
    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\CategoryNodeStorageTransfer> $categoryNodeStorageTransfers
     * @param \ArrayObject<int, \Generated\Shared\Transfer\CategoryNodeSearchResultTransfer> $categoryNodeSearchResultTransfers
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\CategoryNodeSearchResultTransfer>
     */
    public function mapCategoryNodeStoragesToCategoryNodeSearchResults(
        ArrayObject $categoryNodeStorageTransfers,
        ArrayObject $categoryNodeSearchResultTransfers
    ): ArrayObject;
}
