<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesProductConfiguration\Business\Deleter;

use Generated\Shared\Transfer\SalesOrderItemConfigurationCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\SalesOrderItemConfigurationCollectionResponseTransfer;
use Spryker\Zed\SalesProductConfiguration\Persistence\SalesProductConfigurationEntityManagerInterface;

class SalesOrderItemConfigurationDeleter implements SalesOrderItemConfigurationDeleterInterface
{
    /**
     * @param \Spryker\Zed\SalesProductConfiguration\Persistence\SalesProductConfigurationEntityManagerInterface $salesProductConfigurationEntityManager
     */
    public function __construct(protected SalesProductConfigurationEntityManagerInterface $salesProductConfigurationEntityManager)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderItemConfigurationCollectionDeleteCriteriaTransfer $salesOrderItemConfigurationCollectionDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemConfigurationCollectionResponseTransfer
     */
    public function deleteSalesOrderItemConfigurationCollection(
        SalesOrderItemConfigurationCollectionDeleteCriteriaTransfer $salesOrderItemConfigurationCollectionDeleteCriteriaTransfer
    ): SalesOrderItemConfigurationCollectionResponseTransfer {
        if ($salesOrderItemConfigurationCollectionDeleteCriteriaTransfer->getSalesOrderItemIds()) {
            $this->salesProductConfigurationEntityManager->deleteSalesOrderItemConfigurationsBySalesOrderItemIds(
                $salesOrderItemConfigurationCollectionDeleteCriteriaTransfer->getSalesOrderItemIds(),
            );
        }

        return new SalesOrderItemConfigurationCollectionResponseTransfer();
    }
}
