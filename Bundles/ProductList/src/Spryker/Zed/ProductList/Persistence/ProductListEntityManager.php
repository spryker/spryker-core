<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductList\Persistence;

use Generated\Shared\Transfer\ProductListTransfer;
use Orm\Zed\ProductList\Persistence\SpyProductListCategory;
use Orm\Zed\ProductList\Persistence\SpyProductListProductConcrete;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\ProductList\Persistence\ProductListPersistenceFactory getFactory()
 */
class ProductListEntityManager extends AbstractEntityManager implements ProductListEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListTransfer
     */
    public function saveProductList(ProductListTransfer $productListTransfer): ProductListTransfer
    {
        $productListCategoryRelationTransfer = $productListTransfer->getProductListCategoryRelation();
        $productListProductConcreteRelationTransfer = $productListTransfer->getProductListProductConcreteRelation();

        $productListEntity = $this->getFactory()
            ->createProductListQuery()
            ->filterByIdProductList($productListTransfer->getIdProductList())
            ->findOneOrCreate();

        $productListEntity->fromArray($productListTransfer->toArray());
        $productListEntity->save();
        $productListTransfer->fromArray($productListEntity->toArray(), true);

        if ($productListCategoryRelationTransfer) {
            $productListCategoryRelationTransfer->setIdProductList($productListTransfer->getIdProductList());
            $productListTransfer->setProductListCategoryRelation($productListCategoryRelationTransfer);
        }

        if ($productListProductConcreteRelationTransfer) {
            $productListProductConcreteRelationTransfer->setIdProductList($productListTransfer->getIdProductList());
            $productListTransfer->setProductListProductConcreteRelation($productListProductConcreteRelationTransfer);
        }

        return $productListTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     *
     * @return void
     */
    public function deleteProductList(ProductListTransfer $productListTransfer): void
    {
        $this->getFactory()
            ->createProductListQuery()
            ->findOneByIdProductList($productListTransfer->getIdProductList())
            ->delete();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     *
     * @return void
     */
    public function deleteProductListProductRelations(ProductListTransfer $productListTransfer): void
    {
        $productListConcreteProductEntities = $this->getFactory()
            ->createProductListProductConcreteQuery()
            ->filterByFkProductList($productListTransfer->getIdProductList())
            ->find();

        foreach ($productListConcreteProductEntities as $productListConcreteProductEntity) {
            $productListConcreteProductEntity->delete();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     *
     * @return void
     */
    public function deleteProductListCategoryRelations(ProductListTransfer $productListTransfer): void
    {
        $productListCategoryEntities = $this->getFactory()
            ->createProductListCategoryQuery()
            ->filterByFkProductList($productListTransfer->getIdProductList())
            ->find();

        foreach ($productListCategoryEntities as $productListCategoryEntity) {
            $productListCategoryEntity->delete();
        }
    }

    /**
     * @param int $idProductList
     * @param int[] $categoryIds
     *
     * @return void
     */
    public function addCategoryRelations(int $idProductList, array $categoryIds): void
    {
        foreach ($categoryIds as $idCategory) {
            $productListCategoryEntity = new SpyProductListCategory();
            $productListCategoryEntity->setFkProductList($idProductList)
                ->setFkCategory($idCategory)
                ->save();
        }
    }

    /**
     * @param int $idProductList
     * @param int[] $categoryIds
     *
     * @return void
     */
    public function removeCategoryRelations(int $idProductList, array $categoryIds): void
    {
        if (count($categoryIds) === 0) {
            return;
        }

        $productListCategoryEntities = $this->getFactory()
            ->createProductListCategoryQuery()
            ->filterByFkProductList($idProductList)
            ->filterByFkCategory_In($categoryIds)
            ->find();

        foreach ($productListCategoryEntities as $productListCategory) {
            $productListCategory->delete();
        }
    }

    /**
     * @param int $idProductList
     * @param int[] $productIds
     *
     * @return void
     */
    public function addProductConcreteRelations(int $idProductList, array $productIds): void
    {
        foreach ($productIds as $idProductConcrete) {
            $productListProductConcreteEntity = new SpyProductListProductConcrete();
            $productListProductConcreteEntity->setFkProductList($idProductList)
                ->setFkProduct($idProductConcrete)
                ->save();
        }
    }

    /**
     * @param int $idProductList
     * @param int[] $productIds
     *
     * @return void
     */
    public function removeProductConcreteRelations(int $idProductList, array $productIds): void
    {
        if (!$productIds) {
            return;
        }

        $productListConcreteProductEntities = $this->getFactory()
            ->createProductListProductConcreteQuery()
            ->filterByFkProductList($idProductList)
            ->filterByFkProduct_In($productIds)
            ->find();

        foreach ($productListConcreteProductEntities as $productListConcreteProductEntity) {
            $productListConcreteProductEntity->delete();
        }
    }
}
