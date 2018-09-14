<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThreshold\Business\SalesOrderThreshold;

use Generated\Shared\Transfer\SalesOrderThresholdTransfer;

interface SalesOrderThresholdWriterInterface
{
    /**
     * @param \Generated\Shared\Transfer\SalesOrderThresholdTransfer $salesOrderThresholdTransfer
     *
     * @throws \Spryker\Zed\SalesOrderThreshold\Business\Strategy\Exception\SalesOrderThresholdTypeNotFoundException
     *
     * @return \Generated\Shared\Transfer\SalesOrderThresholdTransfer
     */
    public function saveSalesOrderThreshold(
        SalesOrderThresholdTransfer $salesOrderThresholdTransfer
    ): SalesOrderThresholdTransfer;
}
