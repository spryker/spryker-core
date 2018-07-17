<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductVolume\Business\PriceProductReader;

use Generated\Shared\Transfer\PriceProductTransfer;

interface PriceProductReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return array
     */
    public function getPriceProductAbstractFromPriceProduct(PriceProductTransfer $priceProductTransfer): array;
}
