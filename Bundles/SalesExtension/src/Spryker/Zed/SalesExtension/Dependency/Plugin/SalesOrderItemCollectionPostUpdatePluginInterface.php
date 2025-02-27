<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesExtension\Dependency\Plugin;

use Generated\Shared\Transfer\SalesOrderItemCollectionResponseTransfer;

/**
 * Implement this plugin interface to add logic after sales order item is updated.
 */
interface SalesOrderItemCollectionPostUpdatePluginInterface
{
    /**
     * Specification:
     * - Executes after a sales order item is updated.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SalesOrderItemCollectionResponseTransfer $salesOrderItemCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemCollectionResponseTransfer
     */
    public function postUpdate(
        SalesOrderItemCollectionResponseTransfer $salesOrderItemCollectionResponseTransfer
    ): SalesOrderItemCollectionResponseTransfer;
}
