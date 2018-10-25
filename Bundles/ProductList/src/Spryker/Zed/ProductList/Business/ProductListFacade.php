<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductList\Business;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\ProductListTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductList\Business\ProductListBusinessFactory getFactory()
 */
class ProductListFacade extends AbstractFacade implements ProductListFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
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
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     */
    public function deleteProductList(ProductListTransfer $productListTransfer): void
    {
        $this->getFactory()
            ->createProductListWriter()
            ->deleteProductList($productListTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return int[]
     */
    public function getProductAbstractBlacklistIdsByIdProductAbstract(int $idProductAbstract): array
    {
        return $this->getFactory()
            ->createProductListReader()
            ->getProductAbstractBlacklistIdsByIdProductAbstract($idProductAbstract);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return int[]
     */
    public function getProductAbstractWhitelistIdsByIdProductAbstract(int $idProductAbstract): array
    {
        return $this->getFactory()
            ->createProductListReader()
            ->getProductAbstractWhitelistIdsByIdProductAbstract($idProductAbstract);
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idProductConcrete
     *
     * @return int[]
     */
    public function getProductAbstractBlacklistIdsByIdProductConcrete(int $idProductConcrete): array
    {
        return $this->getFactory()
            ->createProductListReader()
            ->getProductAbstractBlacklistIdsByIdProductConcrete($idProductConcrete);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idProductConcrete
     *
     * @return int[]
     */
    public function getProductAbstractWhitelistIdsByIdProductConcrete(int $idProductConcrete): array
    {
        return $this->getFactory()
            ->createProductListReader()
            ->getProductAbstractWhitelistIdsByIdProductConcrete($idProductConcrete);
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
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
}
