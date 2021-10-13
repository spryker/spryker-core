<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryStorage\Business\Writer;

interface ProductCategoryStorageWriterInterface
{
    /**
     * @param array<int> $productAbstractIds
     *
     * @return void
     */
    public function writeCollection(array $productAbstractIds): void;

    /**
     * @param array<int> $categoryIds
     * @param bool $allowEmptyCategories
     *
     * @return void
     */
    public function writeCollectionByRelatedCategories(array $categoryIds, bool $allowEmptyCategories): void;
}
