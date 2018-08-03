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

class GlobalHardThresholdFormMapper extends AbstractGlobalThresholdFormMapper implements GlobalThresholdFormMapperInterface
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
            GlobalThresholdType::PREFIX_HARD
        );

        $globalMinimumOrderValueTransfer->getMinimumOrderValue()->setValue($data[GlobalThresholdType::FIELD_HARD_VALUE]);
        $minimumOrderValueTypeTransfer = (new MinimumOrderValueTypeTransfer())
            ->setKey(MinimumOrderValueGuiConstants::HARD_TYPE_STRATEGY)
            ->setThresholdGroup(MinimumOrderValueGuiConstants::GROUP_HARD);
        $globalMinimumOrderValueTransfer->getMinimumOrderValue()->setMinimumOrderValueType($minimumOrderValueTypeTransfer);

        return $globalMinimumOrderValueTransfer;
    }
}
