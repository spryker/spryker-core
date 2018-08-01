<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValueGui\Communication\Form\Mapper;

use Generated\Shared\Transfer\MinimumOrderValueTransfer;
use Generated\Shared\Transfer\MinimumOrderValueTypeTransfer;
use Spryker\Shared\MinimumOrderValueGui\MinimumOrderValueGuiConstants;
use Spryker\Zed\MinimumOrderValueGui\Communication\Form\GlobalThresholdType;

class GlobalSoftThresholdFlexibleFeeFormMapper extends AbstractGlobalThresholdFormMapper implements GlobalThresholdFormMapperInterface
{
    /**
     * @param array $data
     * @param \Generated\Shared\Transfer\MinimumOrderValueTransfer $minimumOrderValueTransfer
     *
     * @return \Generated\Shared\Transfer\MinimumOrderValueTransfer
     */
    public function map(array $data, MinimumOrderValueTransfer $minimumOrderValueTransfer): MinimumOrderValueTransfer
    {
        $minimumOrderValueTransfer = $this->setStoreAndCurrencyToMinimumOrderValueTransfer($data, $minimumOrderValueTransfer);
        $minimumOrderValueTransfer = $this->setLocalizedMessagesToMinimumOrderValueTransfer(
            $data,
            $minimumOrderValueTransfer,
            GlobalThresholdType::PREFIX_SOFT
        );

        $minimumOrderValueTransfer->setValue($data[GlobalThresholdType::FIELD_SOFT_VALUE]);
        $minimumOrderValueTransfer->setFee($data[GlobalThresholdType::FIELD_SOFT_FLEXIBLE_FEE]);

        $minimumOrderValueTypeTransfer = (new MinimumOrderValueTypeTransfer())
            ->setKey(MinimumOrderValueGuiConstants::SOFT_TYPE_STRATEGY_FLEXIBLE)
            ->setThresholdGroup(MinimumOrderValueGuiConstants::GROUP_SOFT);
        $minimumOrderValueTransfer->setMinimumOrderValueType($minimumOrderValueTypeTransfer);

        return $minimumOrderValueTransfer;
    }
}
