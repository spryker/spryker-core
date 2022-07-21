<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferStock\Business\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\ProductOfferStockResultTransfer;

interface ProductOfferStockResultMapperInterface
{
    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ProductOfferStockTransfer> $productOfferStockTransfers
     *
     * @return \Generated\Shared\Transfer\ProductOfferStockResultTransfer
     */
    public function mapProductOfferStockTransfersToProductOfferStockResultTransfer(
        ArrayObject $productOfferStockTransfers
    ): ProductOfferStockResultTransfer;
}
