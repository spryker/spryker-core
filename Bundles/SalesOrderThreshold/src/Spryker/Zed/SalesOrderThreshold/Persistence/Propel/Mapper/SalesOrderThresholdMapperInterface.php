<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThreshold\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\SalesOrderThresholdTransfer;
use Generated\Shared\Transfer\SalesOrderThresholdTypeTransfer;
use Orm\Zed\SalesOrderThreshold\Persistence\SpySalesOrderThreshold;
use Orm\Zed\SalesOrderThreshold\Persistence\SpySalesOrderThresholdType;

interface SalesOrderThresholdMapperInterface
{
    /**
     * @param \Orm\Zed\SalesOrderThreshold\Persistence\SpySalesOrderThresholdType $spySalesOrderThresholdType
     * @param \Generated\Shared\Transfer\SalesOrderThresholdTypeTransfer $salesOrderThresholdTypeTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderThresholdTypeTransfer
     */
    public function mapSalesOrderThresholdTypeEntityToTransfer(
        SpySalesOrderThresholdType $spySalesOrderThresholdType,
        SalesOrderThresholdTypeTransfer $salesOrderThresholdTypeTransfer
    ): SalesOrderThresholdTypeTransfer;

    /**
     * @param \Orm\Zed\SalesOrderThreshold\Persistence\SpySalesOrderThreshold $salesOrderThresholdEntity
     * @param \Generated\Shared\Transfer\SalesOrderThresholdTransfer $salesOrderThresholdTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderThresholdTransfer
     */
    public function mapSalesOrderThresholdEntityToTransfer(
        SpySalesOrderThreshold $salesOrderThresholdEntity,
        SalesOrderThresholdTransfer $salesOrderThresholdTransfer
    ): SalesOrderThresholdTransfer;
}
