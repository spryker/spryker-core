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
     * @phpstan-param array<int> $productIdsToAssign
     *
     * @param int $idCategory
     * @param array $productIdsToAssign
     *
     * @return void
     */
    public function createProductCategoryMappings($idCategory, array $productIdsToAssign)
    {
        $this->productCategoryFacade->createProductCategoryMappings($idCategory, $productIdsToAssign);
    }

    /**
     * @phpstan-param array<int> $productIdsToUnAssign
     *
     * @param int $idCategory
     * @param array $productIdsToUnAssign
     *
     * @return void
     */
    public function removeProductCategoryMappings($idCategory, array $productIdsToUnAssign)
    {
        $this->productCategoryFacade->removeProductCategoryMappings($idCategory, $productIdsToUnAssign);
    }
}
