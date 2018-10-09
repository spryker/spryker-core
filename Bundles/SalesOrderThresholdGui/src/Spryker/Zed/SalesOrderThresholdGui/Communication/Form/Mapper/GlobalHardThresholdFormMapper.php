<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThresholdGui\Communication\Form\Mapper;

use Generated\Shared\Transfer\SalesOrderThresholdTransfer;
use Generated\Shared\Transfer\SalesOrderThresholdTypeTransfer;
use Spryker\Shared\SalesOrderThresholdGui\SalesOrderThresholdGuiConfig;
use Spryker\Zed\SalesOrderThresholdGui\Communication\Form\GlobalThresholdType;

class GlobalHardThresholdFormMapper extends AbstractGlobalThresholdFormMapper implements GlobalThresholdFormMapperInterface
{
    /**
     * @param array $data
     * @param \Generated\Shared\Transfer\SalesOrderThresholdTransfer $salesOrderThresholdTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderThresholdTransfer
     */
    public function map(array $data, SalesOrderThresholdTransfer $salesOrderThresholdTransfer): SalesOrderThresholdTransfer
    {
        $salesOrderThresholdTransfer->setIdSalesOrderThreshold($data[GlobalThresholdType::FIELD_ID_THRESHOLD_HARD]);
        $salesOrderThresholdTransfer = $this->setStoreAndCurrencyToSalesOrderThresholdTransfer($data, $salesOrderThresholdTransfer);
        $salesOrderThresholdTransfer = $this->setLocalizedMessagesToSalesOrderThresholdTransfer(
            $data,
            $salesOrderThresholdTransfer,
            GlobalThresholdType::PREFIX_HARD
        );

        $salesOrderThresholdTransfer->getSalesOrderThresholdValue()
            ->setThreshold($data[GlobalThresholdType::FIELD_HARD_THRESHOLD]);

        $salesOrderThresholdTypeTransfer = (new SalesOrderThresholdTypeTransfer())
            ->setKey(SalesOrderThresholdGuiConfig::HARD_TYPE_STRATEGY)
            ->setThresholdGroup(SalesOrderThresholdGuiConfig::GROUP_HARD);
        $salesOrderThresholdTransfer->getSalesOrderThresholdValue()->setSalesOrderThresholdType($salesOrderThresholdTypeTransfer);

        return $salesOrderThresholdTransfer;
    }
}
