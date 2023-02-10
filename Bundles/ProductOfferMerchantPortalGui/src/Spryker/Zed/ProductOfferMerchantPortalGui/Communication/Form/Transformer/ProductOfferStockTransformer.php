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
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ProductOfferStockTransfer>|mixed $value
     *
     * @return \Generated\Shared\Transfer\ProductOfferStockTransfer
     */
    public function transform($value): ProductOfferStockTransfer
    {
        /** @var \Generated\Shared\Transfer\ProductOfferStockTransfer $productOfferStockTransfer */
        $productOfferStockTransfer = $value[0];

        return $productOfferStockTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferStockTransfer|mixed $value
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ProductOfferStockTransfer>
     */
    public function reverseTransform($value): ArrayObject
    {
        return (new ArrayObject([$value]));
    }
}
