<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\Mapper\ThresholdGroup;

use Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\Type\ThresholdGroup\MerchantRelationshipHardThresholdType;

class MerchantRelationshipHardThresholdFormMapper extends AbstractMerchantRelationshipThresholdFormMapper implements MerchantRelationshipThresholdFormMapperInterface
{
    /**
     * @param array $data
     * @param \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer $merchantRelationshipSalesOrderThresholdTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer
     */
    public function mapFormDataToTransfer(array $data, MerchantRelationshipSalesOrderThresholdTransfer $merchantRelationshipSalesOrderThresholdTransfer): MerchantRelationshipSalesOrderThresholdTransfer
    {
        $merchantRelationshipSalesOrderThresholdTransfer->setIdMerchantRelationshipSalesOrderThreshold($data[MerchantRelationshipHardThresholdType::FIELD_ID_THRESHOLD] ?? null);
        $merchantRelationshipSalesOrderThresholdTransfer = $this->setLocalizedMessagesToSalesOrderThresholdTransfer(
            $data,
            $merchantRelationshipSalesOrderThresholdTransfer
        );

        $merchantRelationshipSalesOrderThresholdTransfer->getSalesOrderThresholdValue()
            ->setThreshold($data[MerchantRelationshipHardThresholdType::FIELD_THRESHOLD]);

        foreach ($this->formExpanderPlugins as $formExpanderPlugin) {
            if ($formExpanderPlugin->getThresholdKey() !== $data[MerchantRelationshipHardThresholdType::FIELD_STRATEGY]) {
                continue;
            }

            $merchantRelationshipSalesOrderThresholdTransfer->setSalesOrderThresholdValue(
                $formExpanderPlugin->mapFormDataToTransfer($data, $merchantRelationshipSalesOrderThresholdTransfer->getSalesOrderThresholdValue())
            );
        }

        return $merchantRelationshipSalesOrderThresholdTransfer;
    }
}
