<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\Form\Mapper;

use Generated\Shared\Transfer\MinimumOrderValueTransfer;
use Generated\Shared\Transfer\MinimumOrderValueTypeTransfer;
use Spryker\Shared\MerchantRelationshipMinimumOrderValueGui\MerchantRelationshipMinimumOrderValueGuiConfig;
use Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\Form\ThresholdType;

class SoftThresholdFixedFeeFormMapper extends AbstractThresholdFormMapper implements ThresholdFormMapperInterface
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
            ThresholdType::PREFIX_SOFT
        );

        $minimumOrderValueTValueTransfer->getThreshold()->setValue($data[ThresholdType::FIELD_SOFT_VALUE]);
        $minimumOrderValueTValueTransfer->getThreshold()->setFee($data[ThresholdType::FIELD_SOFT_FIXED_FEE]);

        $minimumOrderValueTypeTransfer = (new MinimumOrderValueTypeTransfer())
            ->setKey(MerchantRelationshipMinimumOrderValueGuiConfig::SOFT_TYPE_STRATEGY_FIXED)
            ->setThresholdGroup(MerchantRelationshipMinimumOrderValueGuiConfig::GROUP_SOFT);
        $minimumOrderValueTValueTransfer->getThreshold()->setMinimumOrderValueType($minimumOrderValueTypeTransfer);

        return $minimumOrderValueTValueTransfer;
    }
}
