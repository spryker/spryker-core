<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantCommissionConnector\Business;

use Generated\Shared\Transfer\MerchantCommissionCalculationRequestTransfer;
use Generated\Shared\Transfer\RuleEngineClauseTransfer;

interface PriceProductMerchantCommissionConnectorFacadeInterface
{
    /**
     * Specification:
     * - Requires `MerchantCommissionCalculationRequestTransfer.items.sumPrice` to be set.
     * - Requires `MerchantCommissionCalculationRequestTransfer.items.quantity` to be set.
     * - Requires `RuleEngineClauseTransfer.operator` to be set.
     * - Requires `RuleEngineClauseTransfer.value` to be set.
     * - Collects items with unit price that matches the provided clause.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantCommissionCalculationRequestTransfer $merchantCommissionCalculationRequestTransfer
     * @param \Generated\Shared\Transfer\RuleEngineClauseTransfer $ruleEngineClauseTransfer
     *
     * @return list<\Generated\Shared\Transfer\MerchantCommissionCalculationRequestItemTransfer>
     */
    public function collectByProductPrice(
        MerchantCommissionCalculationRequestTransfer $merchantCommissionCalculationRequestTransfer,
        RuleEngineClauseTransfer $ruleEngineClauseTransfer
    ): array;
}
