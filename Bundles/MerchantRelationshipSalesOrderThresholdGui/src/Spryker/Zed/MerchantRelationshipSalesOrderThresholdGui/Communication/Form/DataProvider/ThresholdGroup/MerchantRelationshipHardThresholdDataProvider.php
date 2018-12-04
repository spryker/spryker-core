<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\DataProvider\ThresholdGroup;

use Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\MerchantRelationshipThresholdType;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\Type\ThresholdGroup\MerchantRelationshipHardThresholdType;

class MerchantRelationshipHardThresholdDataProvider extends AbstractMerchantRelationshipThresholdDataProvider implements ThresholdStrategyGroupDataProviderInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer $merchantRelationshipSalesOrderThresholdTransfer
     * @param array $data
     *
     * @return array
     */
    public function mapSalesOrderThresholdValueTransferToFormData(MerchantRelationshipSalesOrderThresholdTransfer $merchantRelationshipSalesOrderThresholdTransfer, array $data): array
    {
        $thresholdData = $data[MerchantRelationshipThresholdType::FIELD_HARD] ?? [];
        $thresholdData[MerchantRelationshipHardThresholdType::FIELD_ID_THRESHOLD] = $merchantRelationshipSalesOrderThresholdTransfer->getIdMerchantRelationshipSalesOrderThreshold();
        $thresholdData[MerchantRelationshipHardThresholdType::FIELD_THRESHOLD] = $merchantRelationshipSalesOrderThresholdTransfer->getSalesOrderThresholdValue()->getThreshold();

        $thresholdData = $this->expandFormData($merchantRelationshipSalesOrderThresholdTransfer, $thresholdData);
        $thresholdData = $this->mapLocalizedMessages($merchantRelationshipSalesOrderThresholdTransfer, $thresholdData);

        $data[MerchantRelationshipThresholdType::FIELD_HARD] = $thresholdData;

        return $data;
    }
}
