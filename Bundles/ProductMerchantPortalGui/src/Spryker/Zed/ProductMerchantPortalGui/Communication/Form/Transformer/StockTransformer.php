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
     * @phpstan-param \ArrayObject<int, \Generated\Shared\Transfer\StockProductTransfer> $stockProductTransfers
     *
     * @param \ArrayObject|\Generated\Shared\Transfer\StockProductTransfer[] $stockProductTransfers
     *
     * @return \Generated\Shared\Transfer\StockProductTransfer
     */
    public function transform($stockProductTransfers): StockProductTransfer
    {
        if ($stockProductTransfers->count() > 0) {
            return $stockProductTransfers->offsetGet(0);
        }

        return new StockProductTransfer();
    }

    /**
     * @phpstan-return \ArrayObject<int, \Generated\Shared\Transfer\StockProductTransfer>
     *
     * @param \Generated\Shared\Transfer\StockProductTransfer $stockProductTransfer
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\StockProductTransfer[]
     */
    public function reverseTransform($stockProductTransfer): ArrayObject
    {
        return (new ArrayObject([$stockProductTransfer]));
    }
}
