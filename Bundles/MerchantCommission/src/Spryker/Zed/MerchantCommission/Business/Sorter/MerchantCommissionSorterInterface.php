<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Business\Sorter;

interface MerchantCommissionSorterInterface
{
    /**
     * @param list<\Generated\Shared\Transfer\MerchantCommissionTransfer> $merchantCommissionTransfers
     *
     * @return list<\Generated\Shared\Transfer\MerchantCommissionTransfer>
     */
    public function sortMerchantCommissionsByPriority(array $merchantCommissionTransfers): array;
}
