<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValueGui\Communication\Form\Mapper;

use Generated\Shared\Transfer\GlobalMinimumOrderValueTransfer;
use Generated\Shared\Transfer\MinimumOrderValueTypeTransfer;
use Spryker\Shared\MinimumOrderValueGui\MinimumOrderValueGuiConstants;
use Spryker\Zed\MinimumOrderValueGui\Communication\Form\GlobalThresholdType;

class GlobalSoftThresholdFlexibleFeeFormMapper extends AbstractGlobalThresholdFormMapper implements GlobalThresholdFormMapperInterface
{
    /**
     * @param array $data
     * @param \Generated\Shared\Transfer\GlobalMinimumOrderValueTransfer $globalMinimumOrderValueTransfer
     *
     * @return \Generated\Shared\Transfer\GlobalMinimumOrderValueTransfer
     */
    public function map(array $data, GlobalMinimumOrderValueTransfer $globalMinimumOrderValueTransfer): GlobalMinimumOrderValueTransfer
    {
        $globalMinimumOrderValueTransfer = $this->setStoreAndCurrencyToGlobalMinimumOrderValueTransfer($data, $globalMinimumOrderValueTransfer);
        $globalMinimumOrderValueTransfer = $this->setLocalizedMessagesToGlobalMinimumOrderValueTransfer(
            $data,
            $globalMinimumOrderValueTransfer,
            GlobalThresholdType::PREFIX_SOFT
        );

        $globalMinimumOrderValueTransfer->getMinimumOrderValue()->setValue($data[GlobalThresholdType::FIELD_SOFT_VALUE]);
        $globalMinimumOrderValueTransfer->getMinimumOrderValue()->setFee($data[GlobalThresholdType::FIELD_SOFT_FLEXIBLE_FEE]);

        $minimumOrderValueTypeTransfer = (new MinimumOrderValueTypeTransfer())
            ->setKey(MinimumOrderValueGuiConstants::SOFT_TYPE_STRATEGY_FLEXIBLE)
            ->setThresholdGroup(MinimumOrderValueGuiConstants::GROUP_SOFT);
        $globalMinimumOrderValueTransfer->getMinimumOrderValue()->setMinimumOrderValueType($minimumOrderValueTypeTransfer);

        return $globalMinimumOrderValueTransfer;
    }
}
