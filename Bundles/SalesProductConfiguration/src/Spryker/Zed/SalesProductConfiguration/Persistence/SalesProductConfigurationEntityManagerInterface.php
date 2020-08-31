<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesProductConfiguration\Persistence;

use Generated\Shared\Transfer\SalesOrderItemConfigurationTransfer;

interface SalesProductConfigurationEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\SalesOrderItemConfigurationTransfer $salesOrderItemConfigurationTransfer
     *
     * @return void
     */
    public function saveSalesOrderItemConfiguration(SalesOrderItemConfigurationTransfer $salesOrderItemConfigurationTransfer): void;
}
