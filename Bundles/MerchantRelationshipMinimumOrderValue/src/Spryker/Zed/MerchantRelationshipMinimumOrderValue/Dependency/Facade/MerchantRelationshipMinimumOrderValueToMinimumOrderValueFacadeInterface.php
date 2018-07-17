<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMinimumOrderValue\Dependency\Facade;

use Generated\Shared\Transfer\MinimumOrderValueTypeTransfer;

interface MerchantRelationshipMinimumOrderValueToMinimumOrderValueFacadeInterface
{
    /**
     * @param string $strategyKey
     *
     * @return \Generated\Shared\Transfer\MinimumOrderValueTypeTransfer
     */
    public function getMinimumOrderValueType(string $strategyKey): MinimumOrderValueTypeTransfer;

    /**
     * @param string $strategyKey
     * @param int $thresholdValue
     * @param int|null $fee
     *
     * @return bool
     */
    public function validateStrategy(
        string $strategyKey,
        int $thresholdValue,
        ?int $fee = null
    ): bool;
}
