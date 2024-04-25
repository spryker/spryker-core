<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Business\Expander;

use ArrayObject;

interface MerchantCommissionAmountExpanderInterface
{
    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantCommissionAmountTransfer> $merchantCommissionAmountTransfers
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantCommissionAmountTransfer>
     */
    public function expandMerchantCommissionAmountsWithCurrency(ArrayObject $merchantCommissionAmountTransfers): ArrayObject;
}
