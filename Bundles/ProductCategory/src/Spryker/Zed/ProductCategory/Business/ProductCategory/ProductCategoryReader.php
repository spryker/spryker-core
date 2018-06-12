<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategory\Business\ProductCategory;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\ProductCategory\Persistence\ProductCategoryRepositoryInterface;

class ProductCategoryReader implements ProductCategoryReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductCategory\Persistence\ProductCategoryRepositoryInterface
     */
    protected $productCategoryRepository;

    /**
     * @param \Spryker\Zed\ProductCategory\Persistence\ProductCategoryRepositoryInterface $productCategoryRepository
     */
    public function __construct(ProductCategoryRepositoryInterface $productCategoryRepository)
    {
        $this->productCategoryRepository = $productCategoryRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string[]
     */
    public function getProductAbstractCategoriesByIdProductAbstract(ProductAbstractTransfer $productAbstractTransfer, LocaleTransfer $localeTransfer): array
    {
        return $this->productCategoryRepository
            ->getProductAbstractCategoriesByIdProductAbstract(
                $productAbstractTransfer,
                $localeTransfer
            );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string[]
     */
    public function getProductConcreteCategoriesByIdProductConcrete(ProductConcreteTransfer $productConcreteTransfer, LocaleTransfer $localeTransfer): array
    {
        return $this->productCategoryRepository
            ->getProductConcreteCategoriesByIdProductConcrete(
                $productConcreteTransfer,
                $localeTransfer
            );
    }
}
