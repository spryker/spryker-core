<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesProductConnector\Business\Deleter;

use Generated\Shared\Transfer\SalesOrderItemMetadataCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\SalesOrderItemMetadataCollectionResponseTransfer;

interface SalesOrderItemMetadataDeleterInterface
{
    /**
     * @param \Generated\Shared\Transfer\SalesOrderItemMetadataCollectionDeleteCriteriaTransfer $salesOrderItemMetadataCollectionDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemMetadataCollectionResponseTransfer
     */
    public function deleteSalesOrderItemMetadataCollection(
        SalesOrderItemMetadataCollectionDeleteCriteriaTransfer $salesOrderItemMetadataCollectionDeleteCriteriaTransfer
    ): SalesOrderItemMetadataCollectionResponseTransfer;
}
