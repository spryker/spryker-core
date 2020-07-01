<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferStock\Communication\Plugin\ProductOffer;

use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductOfferExtension\Dependency\Plugin\ProductOfferPostUpdatePluginInterface;

/**
 * @method \Spryker\Zed\ProductOfferStock\ProductOfferStockConfig getConfig()
 * @method \Spryker\Zed\ProductOfferStock\Business\ProductOfferStockFacadeInterface getFacade()
 */
class ProductOfferStockProductOfferPostUpdatePlugin extends AbstractPlugin implements ProductOfferPostUpdatePluginInterface
{
    /**
     * {@inheritDoc}
     * - Updates existing Product Offer Stock entity in database if ProductOfferStock transfer contains idProductOfferStock property.
     * - Persists new Product Offer Stock entity to database if ProductOfferStock transfer does not contain idProductOfferStock property.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    public function execute(ProductOfferTransfer $productOfferTransfer): ProductOfferTransfer
    {
        $productOfferStockTransfer = $productOfferTransfer->getProductOfferStock();

        if (!$productOfferStockTransfer) {
            return $productOfferTransfer;
        }

        $productOfferStockTransfer->setIdProductOffer($productOfferTransfer->getIdProductOffer());

        if (!$productOfferStockTransfer->getIdProductOfferStock()) {
            $productOfferStockTransfer = $this->getFacade()->create($productOfferStockTransfer);

            return $productOfferTransfer->setProductOfferStock($productOfferStockTransfer);
        }

        $productOfferStockTransfer = $this->getFacade()->update($productOfferStockTransfer);

        return $productOfferTransfer->setProductOfferStock($productOfferStockTransfer);
    }
}
