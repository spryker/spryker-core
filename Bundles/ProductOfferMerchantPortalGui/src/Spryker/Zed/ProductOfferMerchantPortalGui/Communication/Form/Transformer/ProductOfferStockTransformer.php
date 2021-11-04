<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Form\Transformer;

use ArrayObject;
use Generated\Shared\Transfer\ProductOfferStockTransfer;
use Symfony\Component\Form\DataTransformerInterface;

class ProductOfferStockTransformer implements DataTransformerInterface
{
    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ProductOfferStockTransfer> $productOfferStockTransfers
     *
     * @return \Generated\Shared\Transfer\ProductOfferStockTransfer
     */
    public function transform($productOfferStockTransfers): ProductOfferStockTransfer
    {
        /** @var \Generated\Shared\Transfer\ProductOfferStockTransfer $productOfferStockTransfer */
        $productOfferStockTransfer = $productOfferStockTransfers[0];

        return $productOfferStockTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferStockTransfer $productOfferStockTransfer
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ProductOfferStockTransfer>
     */
    public function reverseTransform($productOfferStockTransfer): ArrayObject
    {
        return (new ArrayObject([$productOfferStockTransfer]));
    }
}
