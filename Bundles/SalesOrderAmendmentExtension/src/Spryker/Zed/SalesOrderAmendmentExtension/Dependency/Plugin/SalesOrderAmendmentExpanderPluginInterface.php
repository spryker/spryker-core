<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendmentExtension\Dependency\Plugin;

use Generated\Shared\Transfer\SalesOrderAmendmentCollectionTransfer;

/**
 * Implement this plugin interface to expand sales order amendments.
 */
interface SalesOrderAmendmentExpanderPluginInterface
{
    /**
     * Specification:
     * - Extend sales order amendments with additional data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SalesOrderAmendmentCollectionTransfer $salesOrderAmendmentCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderAmendmentCollectionTransfer
     */
    public function expand(
        SalesOrderAmendmentCollectionTransfer $salesOrderAmendmentCollectionTransfer
    ): SalesOrderAmendmentCollectionTransfer;
}
