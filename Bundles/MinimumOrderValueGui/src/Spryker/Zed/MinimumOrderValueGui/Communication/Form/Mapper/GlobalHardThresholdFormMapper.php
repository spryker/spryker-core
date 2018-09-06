<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValueGui\Communication\Form\Mapper;

use Generated\Shared\Transfer\MinimumOrderValueTransfer;
use Generated\Shared\Transfer\MinimumOrderValueTypeTransfer;
use Spryker\Shared\MinimumOrderValueGui\MinimumOrderValueGuiConfig;
use Spryker\Zed\MinimumOrderValueGui\Communication\Form\GlobalThresholdType;

class GlobalHardThresholdFormMapper extends AbstractGlobalThresholdFormMapper implements GlobalThresholdFormMapperInterface
{
    /**
     * @param array $data
     * @param \Generated\Shared\Transfer\MinimumOrderValueTransfer $minimumOrderValueTValueTransfer
     *
     * @return \Generated\Shared\Transfer\MinimumOrderValueTransfer
     */
    public function map(array $data, MinimumOrderValueTransfer $minimumOrderValueTValueTransfer): MinimumOrderValueTransfer
    {
        $minimumOrderValueTValueTransfer = $this->setStoreAndCurrencyToMinimumOrderValueTransfer($data, $minimumOrderValueTValueTransfer);
        $minimumOrderValueTValueTransfer = $this->setLocalizedMessagesToMinimumOrderValueTransfer(
            $data,
            $minimumOrderValueTValueTransfer,
            GlobalThresholdType::PREFIX_HARD
        );

        $minimumOrderValueTValueTransfer->getMinimumOrderValueThreshold()->setThreshold($data[GlobalThresholdType::FIELD_HARD_THRESHOLD]);
        $minimumOrderValueTypeTransfer = (new MinimumOrderValueTypeTransfer())
            ->setKey(MinimumOrderValueGuiConfig::HARD_TYPE_STRATEGY)
            ->setThresholdGroup(MinimumOrderValueGuiConfig::GROUP_HARD);
        $minimumOrderValueTValueTransfer->getMinimumOrderValueThreshold()->setMinimumOrderValueType($minimumOrderValueTypeTransfer);

        return $minimumOrderValueTValueTransfer;
    }
}
