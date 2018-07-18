<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMinimumOrderValue\Business;

use Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer;

interface MerchantRelationshipMinimumOrderValueFacadeInterface
{
    /**
     * Specification:
     * - Set merchant relationship specfic minimum order value threshold.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer $merchantRelationshipMinimumOrderValueTransfer
     *
     * @throws \Spryker\Zed\MerchantRelationshipMinimumOrderValue\Business\Strategies\Exception\StrategyNotFoundException
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer
     */
    public function setMerchantRelationshipThreshold(
        MerchantRelationshipMinimumOrderValueTransfer $merchantRelationshipMinimumOrderValueTransfer
    ): MerchantRelationshipMinimumOrderValueTransfer;
}
