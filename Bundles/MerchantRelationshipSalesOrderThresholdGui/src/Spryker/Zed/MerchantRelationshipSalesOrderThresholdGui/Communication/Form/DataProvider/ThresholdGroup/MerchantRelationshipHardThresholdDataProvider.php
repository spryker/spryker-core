<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\DataProvider\ThresholdGroup;

use Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\LocalizedMessagesType;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\MerchantRelationshipThresholdType;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\Type\ThresholdGroup\MerchantRelationshipHardThresholdType;

class MerchantRelationshipHardThresholdDataProvider extends AbstractMerchantRelationshipThresholdDataProvider implements ThresholdStrategyDataProviderInterface
{
    /**
     * @param array $data
     * @param \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer $merchantRelationshipSalesOrderThresholdTransfer
     *
     * @return array
     */
    public function getData(array $data, MerchantRelationshipSalesOrderThresholdTransfer $merchantRelationshipSalesOrderThresholdTransfer): array
    {
        $thresholdData = $data[MerchantRelationshipThresholdType::FIELD_HARD] ?? [];
        $thresholdData[MerchantRelationshipHardThresholdType::FIELD_ID_THRESHOLD] = $merchantRelationshipSalesOrderThresholdTransfer->getIdMerchantRelationshipSalesOrderThreshold();
        $thresholdData[MerchantRelationshipHardThresholdType::FIELD_THRESHOLD] = $merchantRelationshipSalesOrderThresholdTransfer->getSalesOrderThresholdValue()->getThreshold();

        foreach ($this->formExpanderPlugins as $formExpanderPlugin) {
            if ($formExpanderPlugin->getThresholdKey() !== $merchantRelationshipSalesOrderThresholdTransfer->getSalesOrderThresholdValue()->getSalesOrderThresholdType()->getKey()) {
                continue;
            }

            $thresholdData[MerchantRelationshipHardThresholdType::FIELD_STRATEGY] = $formExpanderPlugin->getThresholdKey();
            $thresholdData = $formExpanderPlugin->getData($thresholdData, $merchantRelationshipSalesOrderThresholdTransfer->getSalesOrderThresholdValue());
        }

        foreach ($merchantRelationshipSalesOrderThresholdTransfer->getLocalizedMessages() as $localizedMessage) {
            $thresholdData[$localizedMessage->getLocaleCode()][LocalizedMessagesType::FIELD_MESSAGE] = $localizedMessage->getMessage();
        }

        $data[MerchantRelationshipThresholdType::FIELD_HARD] = $thresholdData;

        return $data;
    }
}
