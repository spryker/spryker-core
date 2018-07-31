<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValueGui\Communication\Form\DataProvider\ThresholdStrategy;

use Generated\Shared\Transfer\MinimumOrderValueTransfer;
use Spryker\Shared\MinimumOrderValueGui\MinimumOrderValueGuiConstants;
use Spryker\Zed\MinimumOrderValueGui\Communication\Form\DataProvider\GlobalThresholdDataProvider;
use Spryker\Zed\MinimumOrderValueGui\Communication\Form\GlobalThresholdType;
use Spryker\Zed\MinimumOrderValueGui\Communication\Form\LocalizedForm;

class SoftThresholdDataProvider implements ThresholdStrategyDataProviderInterface
{
    /**
     * @param array $data
     * @param \Generated\Shared\Transfer\MinimumOrderValueTransfer $minimumOrderValueTransfer
     *
     * @return array
     */
    public function getData(array $data, MinimumOrderValueTransfer $minimumOrderValueTransfer): array
    {
        $data[GlobalThresholdType::FIELD_SOFT_VALUE] = $minimumOrderValueTransfer->getValue();
        $data[GlobalThresholdType::FIELD_SOFT_STRATEGY] = MinimumOrderValueGuiConstants::SOFT_TYPE_STRATEGY_MESSAGE;

        foreach ($minimumOrderValueTransfer->getLocalizedMessages() as $localizedMessage) {
            $localizedFormName = GlobalThresholdType::getLocalizedFormName(GlobalThresholdType::PREFIX_SOFT, $localizedMessage->getLocaleCode());
            $data[$localizedFormName][LocalizedForm::FIELD_MESSAGE] = $localizedMessage->getMessage();
        }

        return $data;
    }
}
