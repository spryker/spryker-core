<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Business\Adder;

use Generated\Shared\Transfer\MerchantCommissionTransfer;

interface MerchantCommissionAdderInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionTransfer $merchantCommissionTransfer
     * @param list<\Generated\Shared\Transfer\MerchantCommissionCalculationRequestItemTransfer> $collectedMerchantCommissionCalculationRequestItems
     * @param array<int, \Generated\Shared\Transfer\CollectedMerchantCommissionTransfer> $collectedMerchantCommissionTransfers
     *
     * @return array<int, \Generated\Shared\Transfer\CollectedMerchantCommissionTransfer>
     */
    public function addCommissionableItemsToCollectedMerchantCommissions(
        MerchantCommissionTransfer $merchantCommissionTransfer,
        array $collectedMerchantCommissionCalculationRequestItems,
        array $collectedMerchantCommissionTransfers
    ): array;
}
