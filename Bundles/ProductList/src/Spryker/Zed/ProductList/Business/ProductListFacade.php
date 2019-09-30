<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductList\Business;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\ProductListResponseTransfer;
use Generated\Shared\Transfer\ProductListTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductList\Business\ProductListBusinessFactory getFactory()
 * @method \Spryker\Zed\ProductList\Persistence\ProductListEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ProductList\Persistence\ProductListRepositoryInterface getRepository()
 */
class ProductListFacade extends AbstractFacade implements ProductListFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use createProductList() or updateProductList() instead.
     *
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListTransfer
     */
    public function saveProductList(ProductListTransfer $productListTransfer): ProductListTransfer
    {
        return $this->getFactory()
            ->createProductListWriter()
            ->saveProductList($productListTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListResponseTransfer
     */
    public function createProductList(ProductListTransfer $productListTransfer): ProductListResponseTransfer
    {
        return $this->getFactory()
            ->createProductListWriter()
            ->createProductList($productListTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListResponseTransfer
     */
    public function updateProductList(ProductListTransfer $productListTransfer): ProductListResponseTransfer
    {
        return $this->getFactory()
            ->createProductListWriter()
            ->updateProductList($productListTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     *
     * @return void
     */
    public function deleteProductList(ProductListTransfer $productListTransfer): void
    {
        $this->getFactory()
            ->createProductListWriter()
            ->deleteProductList($productListTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use ProductListFacade::getProductBlacklistIdsByIdProductAbstract() instead.
     *
     * @param int $idProductAbstract
     *
     * @return int[]
     */
    public function getProductAbstractBlacklistIdsByIdProductAbstract(int $idProductAbstract): array
    {
        return $this->getProductBlacklistIdsByIdProductAbstract($idProductAbstract);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return int[]
     */
    public function getProductBlacklistIdsByIdProductAbstract(int $idProductAbstract): array
    {
        return $this->getFactory()
            ->createProductListReader()
            ->getProductBlacklistIdsByIdProductAbstract($idProductAbstract);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int[] $productAbstractIds
     *
     * @return array
     */
    public function getProductAbstractListIdsByProductAbstractIds(array $productAbstractIds): array
    {
        return $this->getFactory()
            ->createProductListReader()
            ->getProductAbstractListIdsByProductAbstractIds($productAbstractIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int[] $productIds
     *
     * @return array
     */
    public function getProductListsIdsByProductIds(array $productIds): array
    {
        return $this->getFactory()
            ->createProductListReader()
            ->getProductListsByProductIds($productIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use ProductListFacade::getProductWhitelistIdsByIdProductAbstract() instead.
     *
     * @param int $idProductAbstract
     *
     * @return int[]
     */
    public function getProductAbstractWhitelistIdsByIdProductAbstract(int $idProductAbstract): array
    {
        return $this->getProductWhitelistIdsByIdProductAbstract($idProductAbstract);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return int[]
     */
    public function getProductWhitelistIdsByIdProductAbstract(int $idProductAbstract): array
    {
        return $this->getFactory()
            ->createProductListReader()
            ->getProductWhitelistIdsByIdProductAbstract($idProductAbstract);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return int[]
     */
    public function getCategoryWhitelistIdsByIdProductAbstract(int $idProductAbstract): array
    {
        return $this->getFactory()
            ->createProductListReader()
            ->getCategoryWhitelistIdsByIdProductAbstract($idProductAbstract);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use ProductListFacade::getProductBlacklistIdsByIdProduct() instead.
     *
     * @param int $idProduct
     *
     * @return int[]
     */
    public function getProductAbstractBlacklistIdsByIdProductConcrete(int $idProduct): array
    {
        return $this->getProductBlacklistIdsByIdProduct($idProduct);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProduct
     *
     * @return int[]
     */
    public function getProductBlacklistIdsByIdProduct(int $idProduct): array
    {
        return $this->getFactory()
            ->createProductListReader()
            ->getProductBlacklistIdsByIdProduct($idProduct);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use ProductListFacade::getProductWhitelistIdsByIdProduct() instead.
     *
     * @param int $idProduct
     *
     * @return int[]
     */
    public function getProductAbstractWhitelistIdsByIdProductConcrete(int $idProduct): array
    {
        return $this->getProductWhitelistIdsByIdProduct($idProduct);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProduct
     *
     * @return int[]
     */
    public function getProductWhitelistIdsByIdProduct(int $idProduct): array
    {
        return $this->getFactory()
            ->createProductListReader()
            ->getProductWhitelistIdsByIdProduct($idProduct);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListTransfer
     */
    public function getProductListById(ProductListTransfer $productListTransfer): ProductListTransfer
    {
        return $this->getFactory()
            ->createProductListReader()
            ->getProductListById($productListTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int[] $productListIds
     *
     * @return int[]
     */
    public function getProductAbstractIdsByProductListIds(array $productListIds): array
    {
        return $this->getFactory()
            ->createProductListReader()
            ->getProductAbstractIdsByProductListIds($productListIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function validateItemAddProductListRestrictions(CartChangeTransfer $cartChangeTransfer): CartPreCheckResponseTransfer
    {
        return $this->getFactory()
            ->createProductListRestrictionValidator()
            ->validateItemAddition($cartChangeTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function filterRestrictedItems(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->getFactory()
            ->createRestrictedItemsFilter()
            ->filterRestrictedItems($quoteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int[] $productListIds
     *
     * @return int[]
     */
    public function getProductConcreteIdsByProductListIds(array $productListIds): array
    {
        return $this->getFactory()
            ->createProductListReader()
            ->getProductConcreteIdsByProductListIds($productListIds);
    }
}
