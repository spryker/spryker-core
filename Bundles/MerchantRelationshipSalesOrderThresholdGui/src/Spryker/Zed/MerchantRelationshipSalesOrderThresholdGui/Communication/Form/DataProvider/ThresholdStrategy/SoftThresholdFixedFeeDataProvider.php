<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\DataProvider\ThresholdStrategy;

use Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer;
use Spryker\Shared\MerchantRelationshipSalesOrderThresholdGui\MerchantRelationshipSalesOrderThresholdGuiConfig;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\LocalizedForm;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\ThresholdType;

class SoftThresholdFixedFeeDataProvider implements ThresholdStrategyDataProviderInterface
{
    /**
     * @param array $data
     * @param \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer $merchantRelationshipSalesOrderThresholdTransfer
     *
     * @return array
     */
    public function getData(array $data, MerchantRelationshipSalesOrderThresholdTransfer $merchantRelationshipSalesOrderThresholdTransfer): array
    {
        $data[ThresholdType::FIELD_ID_MERCHANT_RELATIONSHIP_THRESHOLD_SOFT] = $merchantRelationshipSalesOrderThresholdTransfer->getIdMerchantRelationshipSalesOrderThreshold();
        $data[ThresholdType::FIELD_SOFT_THRESHOLD] = $merchantRelationshipSalesOrderThresholdTransfer->getSalesOrderThresholdValue()->getThreshold();
        $data[ThresholdType::FIELD_SOFT_FIXED_FEE] = $merchantRelationshipSalesOrderThresholdTransfer->getSalesOrderThresholdValue()->getFee();
        $data[ThresholdType::FIELD_SOFT_STRATEGY] = MerchantRelationshipSalesOrderThresholdGuiConfig::SOFT_TYPE_STRATEGY_FIXED;

        foreach ($merchantRelationshipSalesOrderThresholdTransfer->getLocalizedMessages() as $localizedMessage) {
            $localizedFormName = ThresholdType::getLocalizedFormName(ThresholdType::PREFIX_SOFT, $localizedMessage->getLocaleCode());
            $data[$localizedFormName][LocalizedForm::FIELD_MESSAGE] = $localizedMessage->getMessage();
        }

        return $data;
    }
}
