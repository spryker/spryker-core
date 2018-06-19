<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListSearch\Communication\Dependency\Facade;

use Generated\Shared\Transfer\LocaleTransfer;

interface ProductListSearchToProductCategoryFacadeInterface
{
    /**
     * @param int $idCategory
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer[]
     */
    public function getAbstractProductsByIdCategory(int $idCategory, LocaleTransfer $localeTransfer): array;
}
