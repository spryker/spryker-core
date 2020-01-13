<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockProductCategoryGui\Communication\Reader\Product;

interface ProductReaderInterface
{
    /**
     * @param int[]|null $productAbstractIds
     *
     * @return int[]
     */
    public function getProductAbstracts(?array $productAbstractIds = []): array;

    /**
     * @param string $suggestion
     * @param int $page
     *
     * @return array
     */
    public function getProductAbstractPaginatedAutocompleteData(string $suggestion, int $page): array;
}
