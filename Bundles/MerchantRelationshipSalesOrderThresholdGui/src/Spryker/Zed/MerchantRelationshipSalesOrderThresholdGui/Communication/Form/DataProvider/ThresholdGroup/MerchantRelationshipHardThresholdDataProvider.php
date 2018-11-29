<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\DataProvider\ThresholdGroup;

use Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer;
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

        $thresholdData = $this->getExpandersData($thresholdData, $merchantRelationshipSalesOrderThresholdTransfer);
        $thresholdData = $this->getLocalizedMessages($thresholdData, $merchantRelationshipSalesOrderThresholdTransfer);

        $data[MerchantRelationshipThresholdType::FIELD_HARD] = $thresholdData;

        return $data;
    }
}
