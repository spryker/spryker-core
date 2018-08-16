<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMinimumOrderValue\Dependency\Facade;

use Generated\Shared\Transfer\MinimumOrderValueThresholdTransfer;
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
     * @param \Generated\Shared\Transfer\MinimumOrderValueThresholdTransfer $minimumOrderValueThresholdTransfer
     *
     * @return bool
     */
    public function isThresholdValid(
        MinimumOrderValueThresholdTransfer $minimumOrderValueThresholdTransfer
    ): bool {
        return $this->minimumOrderValueFacade->isThresholdValid($minimumOrderValueThresholdTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MinimumOrderValueTypeTransfer $minimumOrderValueTypeTransfer
     *
     * @return \Generated\Shared\Transfer\MinimumOrderValueTypeTransfer
     */
    public function getMinimumOrderValueTypeByKey(
        MinimumOrderValueTypeTransfer $minimumOrderValueTypeTransfer
    ): MinimumOrderValueTypeTransfer {
        return $this->minimumOrderValueFacade->getMinimumOrderValueTypeByKey($minimumOrderValueTypeTransfer);
    }
}
