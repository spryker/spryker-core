<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferStockGui\Communication\Reader\ProductOfferStock;

use Generated\Shared\Transfer\ProductOfferTransfer;

interface ProductOfferStockReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return array<string, mixed>
     */
    public function getProductOfferStockData(ProductOfferTransfer $productOfferTransfer): array;
}
