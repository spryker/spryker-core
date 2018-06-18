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

        $productListTransfer->setProductListCategoryRelation($productListCategoryRelationTransfer);
        $productListTransfer->setProductListProductConcreteRelation($productListProductConcreteRelationTransfer);

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
     * @param int $idProductList
     * @param array $categoryIds
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
     * @param array $categoryIds
     *
     * @return void
     */
    public function removeCategoryRelations(int $idProductList, array $categoryIds): void
    {
        if (count($categoryIds) === 0) {
            return;
        }
        $this->getFactory()
            ->createProductListCategoryQuery()
            ->filterByFkProductList($idProductList)
            ->filterByFkCategory_In($categoryIds)
            ->delete();
    }

    /**
     * @param int $idProductList
     * @param array $productIds
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
     * @param array $productIds
     *
     * @return void
     */
    public function removeProductConcreteRelations(int $idProductList, array $productIds): void
    {
        if (count($productIds) === 0) {
            return;
        }
        $this->getFactory()
            ->createProductListProductConcreteQuery()
            ->filterByFkProductList($idProductList)
            ->filterByFkProduct_In($productIds)
            ->delete();
    }
}
