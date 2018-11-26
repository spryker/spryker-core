<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThresholdGui\Communication\Form\DataProvider\ThresholdGroup;

use Generated\Shared\Transfer\SalesOrderThresholdTransfer;
use Spryker\Zed\SalesOrderThresholdGui\Communication\Form\GlobalThresholdType;
use Spryker\Zed\SalesOrderThresholdGui\Communication\Form\LocalizedMessagesType;
use Spryker\Zed\SalesOrderThresholdGui\Communication\Form\Type\ThresholdGroup\GlobalHardThresholdType;

class GlobalHardThresholdDataProvider extends AbstractGlobalThresholdDataProvider implements ThresholdStrategyDataProviderInterface
{
    /**
     * @param array $data
     * @param \Generated\Shared\Transfer\SalesOrderThresholdTransfer $salesOrderThresholdTransfer
     *
     * @return array
     */
    public function getData(array $data, SalesOrderThresholdTransfer $salesOrderThresholdTransfer): array
    {
        $thresholdData = $data[GlobalThresholdType::FIELD_HARD] ?? [];
        $thresholdData[GlobalHardThresholdType::FIELD_ID_THRESHOLD] = $salesOrderThresholdTransfer->getIdSalesOrderThreshold();
        $thresholdData[GlobalHardThresholdType::FIELD_THRESHOLD] = $salesOrderThresholdTransfer->getSalesOrderThresholdValue()->getThreshold();

        foreach ($this->formExpanderPlugins as $formExpanderPlugin) {
            if ($formExpanderPlugin->getThresholdKey() !== $salesOrderThresholdTransfer->getSalesOrderThresholdValue()->getSalesOrderThresholdType()->getKey()) {
                continue;
            }

            $thresholdData[GlobalHardThresholdType::FIELD_STRATEGY] = $formExpanderPlugin->getThresholdKey();
            $thresholdData = $formExpanderPlugin->getData($thresholdData, $salesOrderThresholdTransfer->getSalesOrderThresholdValue());
        }

        foreach ($salesOrderThresholdTransfer->getLocalizedMessages() as $localizedMessage) {
            $thresholdData[$localizedMessage->getLocaleCode()][LocalizedMessagesType::FIELD_MESSAGE] = $localizedMessage->getMessage();
        }

        $data[GlobalThresholdType::FIELD_HARD] = $thresholdData;

        return $data;
    }
}
