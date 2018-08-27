<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValueGui\Communication\Form\DataProvider\ThresholdStrategy;

use Generated\Shared\Transfer\MinimumOrderValueTransfer;
use Spryker\Shared\MinimumOrderValueGui\MinimumOrderValueGuiConfig;
use Spryker\Zed\MinimumOrderValueGui\Communication\Form\GlobalThresholdType;
use Spryker\Zed\MinimumOrderValueGui\Communication\Form\LocalizedForm;

class SoftThresholdFixedFeeDataProvider implements ThresholdStrategyDataProviderInterface
{
    /**
     * @param array $data
     * @param \Generated\Shared\Transfer\MinimumOrderValueTransfer $minimumOrderValueTValueTransfer
     *
     * @return array
     */
    public function getData(array $data, MinimumOrderValueTransfer $minimumOrderValueTValueTransfer): array
    {
        $data[GlobalThresholdType::FIELD_SOFT_VALUE] = $minimumOrderValueTValueTransfer->getThreshold()->getThreshold();
        $data[GlobalThresholdType::FIELD_SOFT_FIXED_FEE] = $minimumOrderValueTValueTransfer->getThreshold()->getFeeIfThresholdNotMet();
        $data[GlobalThresholdType::FIELD_SOFT_STRATEGY] = MinimumOrderValueGuiConfig::SOFT_TYPE_STRATEGY_FIXED;

        foreach ($minimumOrderValueTValueTransfer->getLocalizedMessages() as $localizedMessage) {
            $localizedFormName = GlobalThresholdType::getLocalizedFormName(GlobalThresholdType::PREFIX_SOFT, $localizedMessage->getLocaleCode());
            $data[$localizedFormName][LocalizedForm::FIELD_MESSAGE] = $localizedMessage->getMessage();
        }

        return $data;
    }
}
