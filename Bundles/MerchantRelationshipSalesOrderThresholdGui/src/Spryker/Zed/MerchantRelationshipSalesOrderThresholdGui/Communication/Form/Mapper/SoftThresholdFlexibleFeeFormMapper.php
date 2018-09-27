<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\Mapper;

use Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer;
use Generated\Shared\Transfer\SalesOrderThresholdTypeTransfer;
use Spryker\Shared\MerchantRelationshipSalesOrderThresholdGui\MerchantRelationshipSalesOrderThresholdGuiConfig;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\ThresholdType;

class SoftThresholdFlexibleFeeFormMapper extends AbstractThresholdFormMapper implements ThresholdFormMapperInterface
{
    /**
     * @param array $data
     * @param \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer $merchantRelationshipSalesOrderThresholdTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer
     */
    public function map(array $data, MerchantRelationshipSalesOrderThresholdTransfer $merchantRelationshipSalesOrderThresholdTransfer): MerchantRelationshipSalesOrderThresholdTransfer
    {
        $merchantRelationshipSalesOrderThresholdTransfer->setIdMerchantRelationshipSalesOrderThreshold($data[ThresholdType::FIELD_ID_MERCHANT_RELATIONSHIP_THRESHOLD_SOFT]);
        $merchantRelationshipSalesOrderThresholdTransfer = $this->setStoreAndCurrencyToSalesOrderThresholdTransfer($data, $merchantRelationshipSalesOrderThresholdTransfer);
        $merchantRelationshipSalesOrderThresholdTransfer = $this->setLocalizedMessagesToSalesOrderThresholdTransfer(
            $data,
            $merchantRelationshipSalesOrderThresholdTransfer,
            ThresholdType::PREFIX_SOFT
        );

        $merchantRelationshipSalesOrderThresholdTransfer->getSalesOrderThresholdValue()
            ->setThreshold($data[ThresholdType::FIELD_SOFT_THRESHOLD])
            ->setFee($data[ThresholdType::FIELD_SOFT_FLEXIBLE_FEE]);

        $salesOrderThresholdTypeTransfer = (new SalesOrderThresholdTypeTransfer())
            ->setKey(MerchantRelationshipSalesOrderThresholdGuiConfig::SOFT_TYPE_STRATEGY_FLEXIBLE)
            ->setThresholdGroup(MerchantRelationshipSalesOrderThresholdGuiConfig::GROUP_SOFT);

        $merchantRelationshipSalesOrderThresholdTransfer->getSalesOrderThresholdValue()->setSalesOrderThresholdType($salesOrderThresholdTypeTransfer);

        return $merchantRelationshipSalesOrderThresholdTransfer;
    }
}
