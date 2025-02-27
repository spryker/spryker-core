<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesExtension\Dependency\Plugin;

use Generated\Shared\Transfer\SalesExpenseCollectionTransfer;

/**
 * Implement this plugin interface to add logic before sales expenses are deleted.
 */
interface SalesExpensePreDeletePluginInterface
{
    /**
     * Specification:
     * - Executed before sales expenses are deleted.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SalesExpenseCollectionTransfer $salesExpenseCollectionTransfer
     *
     * @return void
     */
    public function preDelete(SalesExpenseCollectionTransfer $salesExpenseCollectionTransfer): void;
}
