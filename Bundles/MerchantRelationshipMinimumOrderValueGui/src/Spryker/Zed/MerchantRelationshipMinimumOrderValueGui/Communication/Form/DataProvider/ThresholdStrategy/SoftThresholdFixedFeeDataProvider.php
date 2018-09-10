<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\Form\DataProvider\ThresholdStrategy;

use Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer;
use Spryker\Shared\MerchantRelationshipMinimumOrderValueGui\MerchantRelationshipMinimumOrderValueGuiConfig;
use Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\Form\LocalizedForm;
use Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\Form\ThresholdType;

class SoftThresholdFixedFeeDataProvider implements ThresholdStrategyDataProviderInterface
{
    /**
     * @param array $data
     * @param \Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer $merchantRelationshipMinimumOrderValueTransfer
     *
     * @return array
     */
    public function getData(array $data, MerchantRelationshipMinimumOrderValueTransfer $merchantRelationshipMinimumOrderValueTransfer): array
    {
        $data[ThresholdType::FIELD_SOFT_THRESHOLD] = $merchantRelationshipMinimumOrderValueTransfer->getMinimumOrderValueThreshold()->getThreshold();
        $data[ThresholdType::FIELD_SOFT_FIXED_FEE] = $merchantRelationshipMinimumOrderValueTransfer->getMinimumOrderValueThreshold()->getFee();
        $data[ThresholdType::FIELD_SOFT_STRATEGY] = MerchantRelationshipMinimumOrderValueGuiConfig::SOFT_TYPE_STRATEGY_FIXED;

        foreach ($merchantRelationshipMinimumOrderValueTransfer->getLocalizedMessages() as $localizedMessage) {
            $localizedFormName = ThresholdType::getLocalizedFormName(ThresholdType::PREFIX_SOFT, $localizedMessage->getLocaleCode());
            $data[$localizedFormName][LocalizedForm::FIELD_MESSAGE] = $localizedMessage->getMessage();
        }

        return $data;
    }
}
