<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferStock\Communication\Plugin\ProductOffer;

use ArrayObject;
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
        /** @var \ArrayObject<int, \Generated\Shared\Transfer\ProductOfferStockTransfer> $productOfferStockTransfers */
        $productOfferStockTransfers = $productOfferTransfer->getProductOfferStocks();

        if (!$productOfferStockTransfers->count()) {
            return $productOfferTransfer;
        }

        $savedProductOfferStockTransfers = new ArrayObject();

        foreach ($productOfferStockTransfers as $productOfferStockTransfer) {
            $productOfferStockTransfer->setIdProductOffer($productOfferTransfer->getIdProductOffer());

            if (!$productOfferStockTransfer->getIdProductOfferStock()) {
                $productOfferStockTransfer = $this->getFacade()->create($productOfferStockTransfer);
                $savedProductOfferStockTransfers->append($productOfferStockTransfer);

                continue;
            }

            $productOfferStockTransfer = $this->getFacade()->update($productOfferStockTransfer);
            $savedProductOfferStockTransfers->append($productOfferStockTransfer);
        }

        return $productOfferTransfer->setProductOfferStocks($savedProductOfferStockTransfers);
    }
}
