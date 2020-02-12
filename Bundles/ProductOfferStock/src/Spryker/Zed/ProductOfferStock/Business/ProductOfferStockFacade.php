<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferStock\Business;

use Generated\Shared\Transfer\ProductOfferStockRequestTransfer;
use Generated\Shared\Transfer\ProductOfferStockTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductOfferStock\Business\ProductOfferStockBusinessFactory getFactory()
 * @method \Spryker\Zed\ProductOfferStock\Persistence\ProductOfferStockRepositoryInterface getRepository()
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
     * @throws \Spryker\Zed\ProductOfferStock\Business\Exception\ProductOfferNotFoundException
     *
     * @return \Generated\Shared\Transfer\ProductOfferStockTransfer
     */
    public function getProductOfferStock(ProductOfferStockRequestTransfer $productOfferStockRequestTransfer): ProductOfferStockTransfer
    {
        return $this->getFactory()
            ->createProductOfferStockReader()
            ->getProductOfferStock($productOfferStockRequestTransfer);
    }
}
