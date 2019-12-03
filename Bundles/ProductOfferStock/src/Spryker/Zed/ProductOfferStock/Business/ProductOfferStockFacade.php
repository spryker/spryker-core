<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferStock\Business;

use Generated\Shared\Transfer\ProductOfferStockRequestTransfer;
use Spryker\DecimalObject\Decimal;
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
     * @param \Generated\Shared\Transfer\ProductOfferStockCriteriaFilterTransfer $productOfferStockCriteriaFilterTransfer
     *
     * @return bool
     */
    public function isProductOfferNeverOutOfStock(ProductOfferStockRequestTransfer $productOfferStockRequestTransfer): bool
    {
        return $this->getFactory()
            ->createProductOfferStockReader()
            ->isProductOfferNeverOutOfStock($productOfferStockRequestTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferStockRequestTransfer $productOfferStockRequestTransfer
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    public function calculateProductOfferStockForRequest(ProductOfferStockRequestTransfer $productOfferStockRequestTransfer): Decimal
    {
        return $this->getRepository()
            ->getProductOfferStockForRequest($productOfferStockRequestTransfer);
    }
}
