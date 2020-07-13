<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Form\Transformer;

use ArrayObject;
use Symfony\Component\Form\DataTransformerInterface;

class ProductOfferStockTransformer implements DataTransformerInterface
{
    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ProductOfferStockTransfer[] $productOfferStockTransfers
     *
     * @return \Generated\Shared\Transfer\ProductOfferStockTransfer
     */
    public function transform($productOfferStockTransfers)
    {
        return $productOfferStockTransfers[0];
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferStockTransfer $productOfferStockTransfer
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ProductOfferStockTransfer[]
     */
    public function reverseTransform($productOfferStockTransfer)
    {
        return (new ArrayObject([$productOfferStockTransfer]));
    }
}
