<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferStock\Business;

use ArrayObject;
use Generated\Shared\Transfer\ProductOfferStockRequestTransfer;
use Generated\Shared\Transfer\ProductOfferStockResultTransfer;
use Generated\Shared\Transfer\ProductOfferStockTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductOfferStock\Business\ProductOfferStockBusinessFactory getFactory()
 * @method \Spryker\Zed\ProductOfferStock\Persistence\ProductOfferStockRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductOfferStock\Persistence\ProductOfferStockEntityManagerInterface getEntityManager()
 */
class ProductOfferStockFacade extends AbstractFacade implements ProductOfferStockFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferStockRequestTransfer $productOfferStockRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferStockResultTransfer
     */
    public function getProductOfferStockResult(
        ProductOfferStockRequestTransfer $productOfferStockRequestTransfer
    ): ProductOfferStockResultTransfer {
        return $this->getFactory()
            ->createProductOfferStockReader()
            ->getProductOfferStockResult($productOfferStockRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferStockRequestTransfer $productOfferStockRequestTransfer
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ProductOfferStockTransfer>
     */
    public function getProductOfferStocks(ProductOfferStockRequestTransfer $productOfferStockRequestTransfer): ArrayObject
    {
        return $this->getFactory()
            ->createProductOfferStockReader()
            ->getProductOfferStocks($productOfferStockRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferStockTransfer $productOfferStockTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferStockTransfer
     */
    public function create(ProductOfferStockTransfer $productOfferStockTransfer): ProductOfferStockTransfer
    {
        return $this->getEntityManager()->create($productOfferStockTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferStockTransfer $productOfferStockTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferStockTransfer
     */
    public function update(ProductOfferStockTransfer $productOfferStockTransfer): ProductOfferStockTransfer
    {
        return $this->getEntityManager()->update($productOfferStockTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    public function expandProductOfferWithProductOfferStockCollection(ProductOfferTransfer $productOfferTransfer): ProductOfferTransfer
    {
        return $this->getFactory()
            ->createProductOfferExpander()
            ->expandProductOfferWithProductOfferStockCollection($productOfferTransfer);
    }
}
