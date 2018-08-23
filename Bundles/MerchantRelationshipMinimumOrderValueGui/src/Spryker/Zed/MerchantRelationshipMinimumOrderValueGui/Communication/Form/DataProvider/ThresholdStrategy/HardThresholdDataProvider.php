<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\Form\DataProvider\ThresholdStrategy;

use Generated\Shared\Transfer\MinimumOrderValueTransfer;
use Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\Form\LocalizedForm;
use Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\Form\ThresholdType;

class HardThresholdDataProvider implements ThresholdStrategyDataProviderInterface
{
    /**
     * @param array $data
     * @param \Generated\Shared\Transfer\MinimumOrderValueTransfer $minimumOrderValueTValueTransfer
     *
     * @return array
     */
    public function getData(array $data, MinimumOrderValueTransfer $minimumOrderValueTValueTransfer): array
    {
        $data[ThresholdType::FIELD_HARD_VALUE] = $minimumOrderValueTValueTransfer->getThreshold()->getValue();

        foreach ($minimumOrderValueTValueTransfer->getLocalizedMessages() as $localizedMessage) {
            $localizedFormName = ThresholdType::getLocalizedFormName(ThresholdType::PREFIX_HARD, $localizedMessage->getLocaleCode());
            $data[$localizedFormName][LocalizedForm::FIELD_MESSAGE] = $localizedMessage->getMessage();
        }

        return $data;
    }
}
