<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryStorage\Business\Reader;

interface ProductAbstractReaderInterface
{
    /**
     * @param array<int> $categoryIds
     *
     * @return array<int>
     */
    public function getProductAbstractIdsByCategoryIds(array $categoryIds): array;

    /**
     * @param array<int> $categoryIds
     *
     * @return array<int>
     */
    public function getRelatedCategoryIds(array $categoryIds): array;
}
