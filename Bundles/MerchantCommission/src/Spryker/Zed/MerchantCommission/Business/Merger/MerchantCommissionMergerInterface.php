<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Business\Merger;

interface MerchantCommissionMergerInterface
{
    /**
     * @param array<string, list<\Generated\Shared\Transfer\CollectedMerchantCommissionTransfer>> $collectedMerchantCommissionTransfersGroupedByGroupKey
     *
     * @return array<string, \Generated\Shared\Transfer\CollectedMerchantCommissionTransfer>
     */
    public function mergeCollectedMerchantCommissions(array $collectedMerchantCommissionTransfersGroupedByGroupKey): array;
}
