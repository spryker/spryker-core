<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Business\Formatter;

use Generated\Shared\Transfer\MerchantCommissionAmountFormatRequestTransfer;

interface MerchantCommissionAmountFormatterInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionAmountFormatRequestTransfer $merchantCommissionAmountFormatRequestTransfer
     *
     * @return string
     */
    public function format(MerchantCommissionAmountFormatRequestTransfer $merchantCommissionAmountFormatRequestTransfer): string;
}
