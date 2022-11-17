<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategory\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\ProductCategoryCollectionTransfer;
use Generated\Shared\Transfer\ProductCategoryTransfer;
use Propel\Runtime\Collection\ArrayCollection;
use Propel\Runtime\Collection\ObjectCollection;

class ProductCategoryMapper
{
    /**
     * @var \Spryker\Zed\ProductCategory\Persistence\Propel\Mapper\CategoryMapper
     */
    protected $categoryMapper;

    /**
     * @param \Spryker\Zed\ProductCategory\Persistence\Propel\Mapper\CategoryMapper $categoryMapper
     */
    public function __construct(CategoryMapper $categoryMapper)
    {
        $this->categoryMapper = $categoryMapper;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\ProductCategory\Persistence\SpyProductCategory> $productCategoryEntities
     * @param \Generated\Shared\Transfer\ProductCategoryCollectionTransfer $productCategoryCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductCategoryCollectionTransfer
     */
    public function mapProductCategoryEntitiesToProductCategoryCollectionTransfer(
        ObjectCollection $productCategoryEntities,
        ProductCategoryCollectionTransfer $productCategoryCollectionTransfer
    ): ProductCategoryCollectionTransfer {
        foreach ($productCategoryEntities as $productCategoryEntity) {
            $categoryTransfer = $this->categoryMapper->mapCategoryEntityToCategoryTransfer(
                $productCategoryEntity->getSpyCategory(),
                new CategoryTransfer(),
            );

            $productCategoryTransfer = (new ProductCategoryTransfer())
                ->fromArray($productCategoryEntity->toArray(), true)
                ->setCategory($categoryTransfer);

            $productCategoryCollectionTransfer->addProductCategory($productCategoryTransfer);
        }

        return $productCategoryCollectionTransfer;
    }

    /**
     * @param \Propel\Runtime\Collection\ArrayCollection<array> $productCategories
     * @param \Generated\Shared\Transfer\ProductCategoryCollectionTransfer $productCategoryCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductCategoryCollectionTransfer
     */
    public function mapProductCategoryArrayToProductCategoryCollectionTransfer(
        ArrayCollection $productCategories,
        ProductCategoryCollectionTransfer $productCategoryCollectionTransfer
    ): ProductCategoryCollectionTransfer {
        foreach ($productCategories as $productCategory) {
            $productCategoryTransfer = (new ProductCategoryTransfer())
                ->fromArray($productCategory, true);

            if (isset($productCategory['SpyCategory'])) {
                $categoryTransfer = $this->categoryMapper->mapCategoryArrayToCategoryTransfer(
                    $productCategory['SpyCategory'],
                    new CategoryTransfer(),
                );

                $productCategoryTransfer->setCategory($categoryTransfer);
            }

            $productCategoryCollectionTransfer->addProductCategory($productCategoryTransfer);
        }

        return $productCategoryCollectionTransfer;
    }
}
