<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMinimumOrderValue\Dependency\Facade;

use Generated\Shared\Transfer\MinimumOrderValueTypeTransfer;

class MerchantRelationshipMinimumOrderValueToMinimumOrderValueFacadeBridge implements MerchantRelationshipMinimumOrderValueToMinimumOrderValueFacadeInterface
{
    /**
     * @var \Spryker\Zed\MinimumOrderValue\Business\MinimumOrderValueFacadeInterface
     */
    protected $minimumOrderValueFacade;

    /**
     * @param \Spryker\Zed\MinimumOrderValue\Business\MinimumOrderValueFacadeInterface $minimumOrderValueFacade
     */
    public function __construct($minimumOrderValueFacade)
    {
        $this->minimumOrderValueFacade = $minimumOrderValueFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\MinimumOrderValueTypeTransfer $minimumOrderValueTypeTransfer
     *
     * @return \Generated\Shared\Transfer\MinimumOrderValueTypeTransfer
     */
    public function getMinimumOrderValueType(
        MinimumOrderValueTypeTransfer $minimumOrderValueTypeTransfer
    ): MinimumOrderValueTypeTransfer {
        return $this->minimumOrderValueFacade->getMinimumOrderValueType($minimumOrderValueTypeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MinimumOrderValueTypeTransfer $minimumOrderValueTypeTransfer
     * @param int $thresholdValue
     * @param int|null $fee
     *
     * @return bool
     */
    public function isStrategyValid(
        MinimumOrderValueTypeTransfer $minimumOrderValueTypeTransfer,
        int $thresholdValue,
        ?int $fee = null
    ): bool {
        return $this->minimumOrderValueFacade
            ->isStrategyValid($minimumOrderValueTypeTransfer, $thresholdValue, $fee);
    }
}
