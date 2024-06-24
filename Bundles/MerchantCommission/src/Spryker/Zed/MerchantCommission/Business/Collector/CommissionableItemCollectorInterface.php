<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Business\Collector;

use Generated\Shared\Transfer\MerchantCommissionCalculationRequestTransfer;

interface CommissionableItemCollectorInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionCalculationRequestTransfer $merchantCommissionCalculationRequestTransfer
     * @param list<\Generated\Shared\Transfer\MerchantCommissionTransfer> $merchantCommissionTransfers
     *
     * @return array<string, \Generated\Shared\Transfer\CollectedMerchantCommissionTransfer>
     */
    public function collectCommissionableItems(
        MerchantCommissionCalculationRequestTransfer $merchantCommissionCalculationRequestTransfer,
        array $merchantCommissionTransfers
    ): array;
}
