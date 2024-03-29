<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade;

use Generated\Shared\Transfer\CategoryCollectionTransfer;
use Generated\Shared\Transfer\LocaleTransfer;

class ProductMerchantPortalGuiToProductCategoryFacadeBridge implements ProductMerchantPortalGuiToProductCategoryFacadeInterface
{
    /**
     * @var \Spryker\Zed\ProductCategory\Business\ProductCategoryFacadeInterface
     */
    protected $productCategoryFacade;

    /**
     * @param \Spryker\Zed\ProductCategory\Business\ProductCategoryFacadeInterface $productCategoryFacade
     */
    public function __construct($productCategoryFacade)
    {
        $this->productCategoryFacade = $productCategoryFacade;
    }

    /**
     * @param int $idProductAbstract
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryCollectionTransfer
     */
    public function getCategoryTransferCollectionByIdProductAbstract(int $idProductAbstract, LocaleTransfer $localeTransfer): CategoryCollectionTransfer
    {
        return $this->productCategoryFacade->getCategoryTransferCollectionByIdProductAbstract($idProductAbstract, $localeTransfer);
    }

    /**
     * @param int $idCategory
     * @param array<int> $productIdsToAssign
     *
     * @return void
     */
    public function createProductCategoryMappings(int $idCategory, array $productIdsToAssign): void
    {
        $this->productCategoryFacade->createProductCategoryMappings($idCategory, $productIdsToAssign);
    }

    /**
     * @param int $idCategory
     * @param array<int> $productIdsToUnAssign
     *
     * @return void
     */
    public function removeProductCategoryMappings(int $idCategory, array $productIdsToUnAssign): void
    {
        $this->productCategoryFacade->removeProductCategoryMappings($idCategory, $productIdsToUnAssign);
    }
}
