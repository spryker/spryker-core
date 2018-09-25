<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThresholdGui\Communication\Form\DataProvider\ThresholdStrategy;

use Generated\Shared\Transfer\SalesOrderThresholdTransfer;
use Spryker\Shared\SalesOrderThresholdGui\SalesOrderThresholdGuiConfig;
use Spryker\Zed\SalesOrderThresholdGui\Communication\Form\GlobalThresholdType;
use Spryker\Zed\SalesOrderThresholdGui\Communication\Form\LocalizedForm;

class SoftThresholdDataProvider implements ThresholdStrategyDataProviderInterface
{
    /**
     * @param array $data
     * @param \Generated\Shared\Transfer\SalesOrderThresholdTransfer $salesOrderThresholdTransfer
     *
     * @return array
     */
    public function getData(array $data, SalesOrderThresholdTransfer $salesOrderThresholdTransfer): array
    {
        $data[GlobalThresholdType::FIELD_ID_THRESHOLD_SOFT] = $salesOrderThresholdTransfer->getIdSalesOrderThreshold();
        $data[GlobalThresholdType::FIELD_SOFT_THRESHOLD] = $salesOrderThresholdTransfer->getSalesOrderThresholdValue()->getThreshold();
        $data[GlobalThresholdType::FIELD_SOFT_STRATEGY] = SalesOrderThresholdGuiConfig::SOFT_TYPE_STRATEGY_MESSAGE;

        foreach ($salesOrderThresholdTransfer->getLocalizedMessages() as $localizedMessage) {
            $localizedFormName = GlobalThresholdType::getLocalizedFormName(GlobalThresholdType::PREFIX_SOFT, $localizedMessage->getLocaleCode());
            $data[$localizedFormName][LocalizedForm::FIELD_MESSAGE] = $localizedMessage->getMessage();
        }

        return $data;
    }
}
