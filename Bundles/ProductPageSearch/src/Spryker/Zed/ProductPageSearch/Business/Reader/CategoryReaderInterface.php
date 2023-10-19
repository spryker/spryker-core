<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Business\Reader;

interface CategoryReaderInterface
{
    /**
     * @param list<int> $categoryIds
     *
     * @return list<int>
     */
    public function getRelatedCategoryIdsByCategoryIds(array $categoryIds): array;
}
