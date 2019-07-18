<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductList\Persistence;

use Generated\Shared\Transfer\ProductListTransfer;
use Orm\Zed\ProductList\Persistence\SpyProductList;
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
    public function createProductList(ProductListTransfer $productListTransfer): ProductListTransfer
    {
        $productListTransfer = $this->saveProductListEntity(new SpyProductList(), $productListTransfer);

        return $productListTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListTransfer
     */
    public function updateProductList(ProductListTransfer $productListTransfer): ProductListTransfer
    {
        $productListTransfer->requireIdProductList();

        $productListEntity = $this->getFactory()
            ->createProductListQuery()
            ->findOneByIdProductList($productListTransfer->getIdProductList());

        $productListTransfer = $this->saveProductListEntity($productListEntity, $productListTransfer);

        return $productListTransfer;
    }

    /**
     * @param \Orm\Zed\ProductList\Persistence\SpyProductList $productListEntity
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListTransfer
     */
    protected function saveProductListEntity(SpyProductList $productListEntity, ProductListTransfer $productListTransfer): ProductListTransfer
    {
        $productListEntity = $this->getFactory()
            ->createProductListMapper()
            ->mapProductListTransferToEntity($productListTransfer, $productListEntity);

        $productListEntity->save();

        $productListTransfer = $this->getFactory()
            ->createProductListMapper()
            ->mapEntityToProductListTransfer($productListEntity, $productListTransfer);

        $productListTransfer = $this->saveProductListRelation($productListTransfer);

        return $productListTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListTransfer
     */
    protected function saveProductListRelation(ProductListTransfer $productListTransfer): ProductListTransfer
    {
        $productListCategoryRelationTransfer = $productListTransfer->getProductListCategoryRelation();
        $productListProductConcreteRelationTransfer = $productListTransfer->getProductListProductConcreteRelation();

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
        foreach ($productIds as $idProduct) {
            $productListProductConcreteEntity = new SpyProductListProductConcrete();
            $productListProductConcreteEntity->setFkProductList($idProductList)
                ->setFkProduct($idProduct)
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
