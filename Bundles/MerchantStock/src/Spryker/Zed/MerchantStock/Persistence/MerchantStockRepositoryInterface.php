<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantStock\Persistence;

use ArrayObject;
use Generated\Shared\Transfer\MerchantTransfer;

interface MerchantStockRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\StockTransfer[]|\ArrayObject
     */
    public function getStocksByMerchant(MerchantTransfer $merchantTransfer): ArrayObject;
}
