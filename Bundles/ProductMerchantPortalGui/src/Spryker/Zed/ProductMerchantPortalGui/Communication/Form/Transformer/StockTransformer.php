<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Form\Transformer;

use ArrayObject;
use Generated\Shared\Transfer\StockProductTransfer;
use Symfony\Component\Form\DataTransformerInterface;

class StockTransformer implements DataTransformerInterface
{
    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\StockProductTransfer>|mixed $value
     *
     * @return \Generated\Shared\Transfer\StockProductTransfer
     */
    public function transform($value): StockProductTransfer
    {
        if ($value->count() > 0) {
            return $value->offsetGet(0) ?: new StockProductTransfer();
        }

        return new StockProductTransfer();
    }

    /**
     * @param \Generated\Shared\Transfer\StockProductTransfer|mixed $value
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\StockProductTransfer>
     */
    public function reverseTransform($value): ArrayObject
    {
        return (new ArrayObject([$value]));
    }
}
