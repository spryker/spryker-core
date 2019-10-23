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
     * @param array $productAbstractIds
     * @param string $locale
     *
     * @return \Generated\Shared\Transfer\ProductAbstractCategoryStorageTransfer[]|null
     */
    public function findBulkProductAbstractCategory(array $productAbstractIds, $locale): ?array;
}
