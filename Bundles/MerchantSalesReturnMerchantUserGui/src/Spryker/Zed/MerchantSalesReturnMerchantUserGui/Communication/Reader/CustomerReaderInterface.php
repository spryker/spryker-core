<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesReturnMerchantUserGui\Communication\Reader;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ReturnTransfer;

interface CustomerReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ReturnTransfer $returnTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function getCustomerFromReturn(ReturnTransfer $returnTransfer): CustomerTransfer;
}
