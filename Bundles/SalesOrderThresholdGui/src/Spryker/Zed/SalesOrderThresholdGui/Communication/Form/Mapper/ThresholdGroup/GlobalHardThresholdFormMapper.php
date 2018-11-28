<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThresholdGui\Communication\Form\Mapper\ThresholdGroup;

use Generated\Shared\Transfer\SalesOrderThresholdTransfer;
use Spryker\Zed\SalesOrderThresholdGui\Communication\Form\Type\ThresholdGroup\GlobalHardThresholdType;

class GlobalHardThresholdFormMapper extends AbstractGlobalThresholdFormMapper implements GlobalThresholdFormMapperInterface
{
    /**
     * @param array $data
     * @param \Generated\Shared\Transfer\SalesOrderThresholdTransfer $salesOrderThresholdTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderThresholdTransfer
     */
    public function mapFormDataToTransfer(array $data, SalesOrderThresholdTransfer $salesOrderThresholdTransfer): SalesOrderThresholdTransfer
    {
        $salesOrderThresholdTransfer->setIdSalesOrderThreshold($data[GlobalHardThresholdType::FIELD_ID_THRESHOLD] ?? null);
        $salesOrderThresholdTransfer = $this->setLocalizedMessagesToSalesOrderThresholdTransfer(
            $data,
            $salesOrderThresholdTransfer
        );

        $salesOrderThresholdTransfer->getSalesOrderThresholdValue()
            ->setThreshold($data[GlobalHardThresholdType::FIELD_THRESHOLD]);

        foreach ($this->formExpanderPlugins as $formExpanderPlugin) {
            if ($formExpanderPlugin->getThresholdKey() !== $data[GlobalHardThresholdType::FIELD_STRATEGY]) {
                continue;
            }

            $salesOrderThresholdTransfer->setSalesOrderThresholdValue(
                $formExpanderPlugin->mapFormDataToTransfer($data, $salesOrderThresholdTransfer->getSalesOrderThresholdValue())
            );
        }

        return $salesOrderThresholdTransfer;
    }
}
