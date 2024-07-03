<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantCommissionConnector\Business;

use Generated\Shared\Transfer\MerchantCommissionCalculationRequestTransfer;
use Generated\Shared\Transfer\RuleEngineClauseTransfer;

interface ProductMerchantCommissionConnectorFacadeInterface
{
    /**
     * Specification:
     * - Requires `MerchantCommissionCalculationRequestTransfer.items.sku` to be set.
     * - Requires `RuleEngineClauseTransfer.attribute` to be set.
     * - Reads combined product attributes from Persistence for each product.
     * - Collects items with attributes that match the provided clause.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantCommissionCalculationRequestTransfer $merchantCommissionCalculationRequestTransfer
     * @param \Generated\Shared\Transfer\RuleEngineClauseTransfer $ruleEngineClauseTransfer
     *
     * @return list<\Generated\Shared\Transfer\MerchantCommissionCalculationRequestItemTransfer>
     */
    public function collectByProductAttribute(
        MerchantCommissionCalculationRequestTransfer $merchantCommissionCalculationRequestTransfer,
        RuleEngineClauseTransfer $ruleEngineClauseTransfer
    ): array;

    /**
     * Specification:
     * - Returns list of all available product attribute keys.
     *
     * @api
     *
     * @return list<string>
     */
    public function getProductAttributeKeys(): array;
}
