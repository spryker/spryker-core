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
     * @param bool $usesCache
     *
     * @return array<array<int>>
     */
    public function buildProductCategoryTree(LocaleTransfer $localeTransfer, StoreTransfer $storeTransfer, bool $usesCache = false): array;

    /**
     * @param array<int> $categoryNodeIds
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array<array<string>>
     */
    public function buildProductCategoryTreeNames(array $categoryNodeIds, LocaleTransfer $localeTransfer): array;
}
