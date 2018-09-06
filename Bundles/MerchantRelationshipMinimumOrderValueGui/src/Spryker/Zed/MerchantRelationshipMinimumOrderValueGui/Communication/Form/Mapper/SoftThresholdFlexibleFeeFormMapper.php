<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\Form\Mapper;

use Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer;
use Generated\Shared\Transfer\MinimumOrderValueTypeTransfer;
use Spryker\Shared\MerchantRelationshipMinimumOrderValueGui\MerchantRelationshipMinimumOrderValueGuiConfig;
use Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\Form\ThresholdType;

class SoftThresholdFlexibleFeeFormMapper extends AbstractThresholdFormMapper implements ThresholdFormMapperInterface
{
    /**
     * @param array $data
     * @param \Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer $merchantRelationshipMinimumOrderValueTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer
     */
    public function map(array $data, MerchantRelationshipMinimumOrderValueTransfer $merchantRelationshipMinimumOrderValueTransfer): MerchantRelationshipMinimumOrderValueTransfer
    {
        $merchantRelationshipMinimumOrderValueTransfer = $this->setStoreAndCurrencyToMinimumOrderValueTransfer($data, $merchantRelationshipMinimumOrderValueTransfer);
        $merchantRelationshipMinimumOrderValueTransfer = $this->setLocalizedMessagesToMinimumOrderValueTransfer(
            $data,
            $merchantRelationshipMinimumOrderValueTransfer,
            ThresholdType::PREFIX_SOFT
        );

        $merchantRelationshipMinimumOrderValueTransfer->getMinimumOrderValueThreshold()->setThreshold($data[ThresholdType::FIELD_SOFT_THRESHOLD]);
        $merchantRelationshipMinimumOrderValueTransfer->getMinimumOrderValueThreshold()->setFee($data[ThresholdType::FIELD_SOFT_FLEXIBLE_FEE]);

        $minimumOrderValueTypeTransfer = (new MinimumOrderValueTypeTransfer())
            ->setKey(MerchantRelationshipMinimumOrderValueGuiConfig::SOFT_TYPE_STRATEGY_FLEXIBLE)
            ->setThresholdGroup(MerchantRelationshipMinimumOrderValueGuiConfig::GROUP_SOFT);
        $merchantRelationshipMinimumOrderValueTransfer->getMinimumOrderValueThreshold()->setMinimumOrderValueType($minimumOrderValueTypeTransfer);

        return $merchantRelationshipMinimumOrderValueTransfer;
    }
}
