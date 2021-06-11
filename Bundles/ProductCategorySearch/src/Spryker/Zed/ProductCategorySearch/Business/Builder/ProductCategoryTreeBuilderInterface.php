<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategorySearch\Business\Builder;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\StoreTransfer;

interface ProductCategoryTreeBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return int[][]
     */
    public function buildProductCategoryTree(LocaleTransfer $localeTransfer, StoreTransfer $storeTransfer): array;

    /**
     * @param int[] $categoryNodeIds
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string[][]
     */
    public function buildProductCategoryTreeNames(array $categoryNodeIds, LocaleTransfer $localeTransfer): array;
}
