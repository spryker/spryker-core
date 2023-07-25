<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferStock\Communication\Plugin\ProductOffer;

use ArrayObject;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductOfferExtension\Dependency\Plugin\ProductOfferPostCreatePluginInterface;

/**
 * @method \Spryker\Zed\ProductOfferStock\ProductOfferStockConfig getConfig()
 * @method \Spryker\Zed\ProductOfferStock\Business\ProductOfferStockFacadeInterface getFacade()
 */
class ProductOfferStockProductOfferPostCreatePlugin extends AbstractPlugin implements ProductOfferPostCreatePluginInterface
{
    /**
     * {@inheritDoc}
     * - Persists new Product Offer Stock entity to database.
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
            $productOfferStockTransfer = $this->getFacade()->create($productOfferStockTransfer);
            $savedProductOfferStockTransfers->append($productOfferStockTransfer);
        }

        return $productOfferTransfer->setProductOfferStocks($savedProductOfferStockTransfers);
    }
}
