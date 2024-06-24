<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Business\Filter;

use Generated\Shared\Transfer\MerchantCommissionCalculationRequestTransfer;
use Generated\Shared\Transfer\MerchantCommissionCollectionTransfer;

interface MerchantCommissionFilterInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionCollectionTransfer $merchantCommissionCollectionTransfer
     * @param \Generated\Shared\Transfer\MerchantCommissionCalculationRequestTransfer $merchantCommissionCalculationRequestTransfer
     * @param array<string, list<\Generated\Shared\Transfer\MerchantCommissionCalculationRequestItemTransfer>> $merchantCommissionCalculationRequestItemsGroupedByMerchantReference
     *
     * @return list<\Generated\Shared\Transfer\MerchantCommissionTransfer>
     */
    public function filterOutNotApplicableMerchantCommissions(
        MerchantCommissionCollectionTransfer $merchantCommissionCollectionTransfer,
        MerchantCommissionCalculationRequestTransfer $merchantCommissionCalculationRequestTransfer,
        array $merchantCommissionCalculationRequestItemsGroupedByMerchantReference
    ): array;
}
