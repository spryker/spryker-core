<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThresholdGui\Communication\Form\Mapper;

use Generated\Shared\Transfer\SalesOrderThresholdTransfer;
use Spryker\Zed\SalesOrderThresholdGui\Communication\Form\GlobalThresholdType;

class AbstractSoftGlobalThresholdFormMapper extends AbstractGlobalThresholdFormMapper
{
    /**
     * @param \Generated\Shared\Transfer\SalesOrderThresholdTransfer $salesOrderThresholdTransfer
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\SalesOrderThresholdTransfer
     */
    protected function setSoftIdSalesOrderThreshold(SalesOrderThresholdTransfer $salesOrderThresholdTransfer, array $data): SalesOrderThresholdTransfer
    {
        $salesOrderThresholdTransfer->setIdSalesOrderThreshold($data[GlobalThresholdType::FIELD_ID_THRESHOLD_SOFT]);

        return $salesOrderThresholdTransfer;
    }
}
