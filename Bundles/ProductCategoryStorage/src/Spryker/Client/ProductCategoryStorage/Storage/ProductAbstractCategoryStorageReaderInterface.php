<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductCategoryStorage\Storage;

interface ProductAbstractCategoryStorageReaderInterface
{
    /**
     * @param int $idProductAbstract
     * @param string $locale
     *
     * @return \Generated\Shared\Transfer\ProductAbstractCategoryStorageTransfer|null
     */
    public function findProductAbstractCategory($idProductAbstract, $locale);

    /**
     * @param int[] $productAbstractIds
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductAbstractCategoryStorageTransfer[]
     */
    public function findBulkProductAbstractCategory(array $productAbstractIds, string $localeName): array;
}
