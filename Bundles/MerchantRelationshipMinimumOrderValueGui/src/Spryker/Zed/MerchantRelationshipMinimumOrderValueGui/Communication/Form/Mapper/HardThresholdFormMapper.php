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

class HardThresholdFormMapper extends AbstractThresholdFormMapper implements ThresholdFormMapperInterface
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
            ThresholdType::PREFIX_HARD
        );

        $merchantRelationshipMinimumOrderValueTransfer->getMinimumOrderValueThreshold()->setThreshold($data[ThresholdType::FIELD_HARD_THRESHOLD]);
        $minimumOrderValueTypeTransfer = (new MinimumOrderValueTypeTransfer())
            ->setKey(MerchantRelationshipMinimumOrderValueGuiConfig::HARD_TYPE_STRATEGY)
            ->setThresholdGroup(MerchantRelationshipMinimumOrderValueGuiConfig::GROUP_HARD);
        $merchantRelationshipMinimumOrderValueTransfer->getMinimumOrderValueThreshold()->setMinimumOrderValueType($minimumOrderValueTypeTransfer);

        return $merchantRelationshipMinimumOrderValueTransfer;
    }
}
