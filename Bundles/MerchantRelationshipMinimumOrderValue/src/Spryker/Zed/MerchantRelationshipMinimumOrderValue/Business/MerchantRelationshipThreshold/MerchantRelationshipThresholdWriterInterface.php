<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMinimumOrderValue\Business\MerchantRelationshipThreshold;

use Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer;

interface MerchantRelationshipThresholdWriterInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer $merchantRelationshipMinimumOrderValueTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer
     */
    public function saveMerchantRelationshipMinimumOrderValue(
        MerchantRelationshipMinimumOrderValueTransfer $merchantRelationshipMinimumOrderValueTransfer
    ): MerchantRelationshipMinimumOrderValueTransfer;
}
