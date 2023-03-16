<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthWarehouse\Business\Reader;

use Generated\Shared\Transfer\StockCriteriaFilterTransfer;

interface StockReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\StockCriteriaFilterTransfer $stockCriteriaFilterTransfer
     *
     * @return bool
     */
    public function hasStock(StockCriteriaFilterTransfer $stockCriteriaFilterTransfer): bool;
}
