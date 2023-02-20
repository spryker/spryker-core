<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CategoryStorage\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\CategoryNodeSearchResultTransfer;

class CategoryNodeStorageMapper implements CategoryNodeStorageMapperInterface
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
    ): ArrayObject {
        foreach ($categoryNodeStorageTransfers as $categoryNodeStorageTransfer) {
            $categoryNodeSearchResultTransfers->append(
                (new CategoryNodeSearchResultTransfer())->fromArray($categoryNodeStorageTransfer->toArray(), true),
            );
        }

        return $categoryNodeSearchResultTransfers;
    }
}
