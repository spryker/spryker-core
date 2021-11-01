<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade;

use Generated\Shared\Transfer\CategoryCollectionTransfer;
use Generated\Shared\Transfer\LocaleTransfer;

interface ProductMerchantPortalGuiToProductCategoryFacadeInterface
{
    /**
     * @param int $idProductAbstract
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryCollectionTransfer
     */
    public function getCategoryTransferCollectionByIdProductAbstract(int $idProductAbstract, LocaleTransfer $localeTransfer): CategoryCollectionTransfer;

    /**
     * @param int $idCategory
     * @param array<int> $productIdsToAssign
     *
     * @return void
     */
    public function createProductCategoryMappings($idCategory, array $productIdsToAssign);

    /**
     * @param int $idCategory
     * @param array<int> $productIdsToUnAssign
     *
     * @return void
     */
    public function removeProductCategoryMappings($idCategory, array $productIdsToUnAssign);
}
