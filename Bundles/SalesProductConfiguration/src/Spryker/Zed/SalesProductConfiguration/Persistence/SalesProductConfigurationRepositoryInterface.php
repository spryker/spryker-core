<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesProductConfiguration\Persistence;

use Generated\Shared\Transfer\SalesOrderItemConfigurationFilterTransfer;

interface SalesProductConfigurationRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\SalesOrderItemConfigurationFilterTransfer $salesOrderItemConfigurationFilterTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemConfigurationTransfer[]
     */
    public function getSalesOrderItemConfigurationsByFilter(
        SalesOrderItemConfigurationFilterTransfer $salesOrderItemConfigurationFilterTransfer
    ): array;
}
