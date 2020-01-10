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
 * @method \Spryker\Zed\ProductOfferStock\Persistence\ProductOfferStockRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductOfferStock\Business\ProductOfferStockBusinessFactory getFactory()
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
     * @return \Spryker\DecimalObject\Decimal
     */
    public function getProductOfferStock(ProductOfferStockRequestTransfer $productOfferStockRequestTransfer): Decimal
    {
        return $this->getRepository()
            ->getProductOfferStockForRequest($productOfferStockRequestTransfer);
    }
}
