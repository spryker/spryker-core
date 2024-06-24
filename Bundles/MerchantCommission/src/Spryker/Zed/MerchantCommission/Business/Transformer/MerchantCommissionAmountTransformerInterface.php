<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Business\Transformer;

use Generated\Shared\Transfer\MerchantCommissionAmountTransformerRequestTransfer;

interface MerchantCommissionAmountTransformerInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionAmountTransformerRequestTransfer $merchantCommissionAmountTransformerRequestTransfer
     *
     * @return int
     */
    public function transformForPersistence(
        MerchantCommissionAmountTransformerRequestTransfer $merchantCommissionAmountTransformerRequestTransfer
    ): int;

    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionAmountTransformerRequestTransfer $merchantCommissionAmountTransformerRequestTransfer
     *
     * @return float
     */
    public function transformFromPersistence(
        MerchantCommissionAmountTransformerRequestTransfer $merchantCommissionAmountTransformerRequestTransfer
    ): float;
}
