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
     * @phpstan-param array<\Generated\Shared\Transfer\StockProductTransfer> $stockProductTransfers
     *
     * @param \Generated\Shared\Transfer\StockProductTransfer[]|\ArrayObject $stockProductTransfers
     *
     * @return \Generated\Shared\Transfer\StockProductTransfer
     */
    public function transform($stockProductTransfers): StockProductTransfer
    {
        return $stockProductTransfers->offsetGet(0);
    }

    /**
     * @phpstan-return \ArrayObject<int, \Generated\Shared\Transfer\StockProductTransfer>
     *
     * @param \Generated\Shared\Transfer\StockProductTransfer $stockProductTransfer
     *
     * @return \Generated\Shared\Transfer\StockProductTransfer[]|\ArrayObject
     */
    public function reverseTransform($stockProductTransfer): ArrayObject
    {
        return (new ArrayObject([$stockProductTransfer]));
    }
}
