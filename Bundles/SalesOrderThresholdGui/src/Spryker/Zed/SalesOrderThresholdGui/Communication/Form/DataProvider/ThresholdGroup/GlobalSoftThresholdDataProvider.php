<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThresholdGui\Communication\Form\DataProvider\ThresholdGroup;

use Generated\Shared\Transfer\SalesOrderThresholdTransfer;
use Spryker\Zed\SalesOrderThresholdGui\Communication\Form\DataProvider\ThresholdStrategyDataProviderInterface;
use Spryker\Zed\SalesOrderThresholdGui\Communication\Form\GlobalSoftThresholdType;
use Spryker\Zed\SalesOrderThresholdGui\Communication\Form\GlobalThresholdType;
use Spryker\Zed\SalesOrderThresholdGui\Communication\Form\LocalizedForm;

class GlobalSoftThresholdDataProvider extends AbstractGlobalThresholdDataProvider implements ThresholdStrategyDataProviderInterface
{
    /**
     * @param array $data
     * @param \Generated\Shared\Transfer\SalesOrderThresholdTransfer $salesOrderThresholdTransfer
     *
     * @return array
     */
    public function getData(array $data, SalesOrderThresholdTransfer $salesOrderThresholdTransfer): array
    {
        $thresholdData = $data[GlobalThresholdType::FIELD_SOFT] ?? [];
        $thresholdData[GlobalSoftThresholdType::FIELD_ID_THRESHOLD] = $salesOrderThresholdTransfer->getIdSalesOrderThreshold();
        $thresholdData[GlobalSoftThresholdType::FIELD_THRESHOLD] = $salesOrderThresholdTransfer->getSalesOrderThresholdValue()->getThreshold();

        foreach ($this->formExpanderPlugins as $formExpanderPlugin) {
            if ($formExpanderPlugin->getThresholdKey() !== $salesOrderThresholdTransfer->getSalesOrderThresholdValue()->getSalesOrderThresholdType()->getKey()) {
                continue;
            }

            $thresholdData[GlobalSoftThresholdType::FIELD_STRATEGY] = $formExpanderPlugin->getThresholdKey();
            $thresholdData = $formExpanderPlugin->getData($thresholdData, $salesOrderThresholdTransfer);
        }

        foreach ($salesOrderThresholdTransfer->getLocalizedMessages() as $localizedMessage) {
            $thresholdData[$localizedMessage->getLocaleCode()][LocalizedForm::FIELD_MESSAGE] = $localizedMessage->getMessage();
        }

        $data[GlobalThresholdType::FIELD_SOFT] = $thresholdData;

        return $data;
    }
}
