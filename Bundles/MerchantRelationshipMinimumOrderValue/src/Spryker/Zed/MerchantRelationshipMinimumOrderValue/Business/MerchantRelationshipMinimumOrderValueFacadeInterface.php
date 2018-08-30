<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMinimumOrderValue\Business;

use Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface MerchantRelationshipMinimumOrderValueFacadeInterface
{
    /**
     * Specification:
     * - Finds the applicable thresholds for a given QuoteTransfer.
     * - Based on quote the customer and the respective merchant relationships.
     * - Also prepares the minimum order value objects to be provided for the minimum order value strategies.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\MinimumOrderValueThresholdTransfer[]
     */
    public function findApplicableThresholds(QuoteTransfer $quoteTransfer): array;

    /**
     * Specification:
     * - Set merchant relationship specific minimum order value threshold.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer $merchantRelationshipMinimumOrderValueTransfer
     *
     * @throws \Spryker\Zed\MinimumOrderValue\Business\Strategy\Exception\MinimumOrderValueTypeNotFoundException
     * @throws \Spryker\Zed\MinimumOrderValue\Business\Strategy\Exception\MinimumOrderValueThresholdInvalidArgumentException
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer
     */
    public function saveMerchantRelationshipMinimumOrderValue(
        MerchantRelationshipMinimumOrderValueTransfer $merchantRelationshipMinimumOrderValueTransfer
    ): MerchantRelationshipMinimumOrderValueTransfer;
}
