<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReclamation\Business\Deleter;

use Generated\Shared\Transfer\SalesReclamationItemCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\SalesReclamationItemCollectionResponseTransfer;
use Spryker\Zed\SalesReclamation\Persistence\SalesReclamationEntityManagerInterface;

class SalesReclamationItemDeleter implements SalesReclamationItemDeleterInterface
{
    /**
     * @param \Spryker\Zed\SalesReclamation\Persistence\SalesReclamationEntityManagerInterface $salesReclamationEntityManager
     */
    public function __construct(protected SalesReclamationEntityManagerInterface $salesReclamationEntityManager)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\SalesReclamationItemCollectionDeleteCriteriaTransfer $salesReclamationItemCollectionDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SalesReclamationItemCollectionResponseTransfer
     */
    public function deleteSalesReclamationItemCollection(
        SalesReclamationItemCollectionDeleteCriteriaTransfer $salesReclamationItemCollectionDeleteCriteriaTransfer
    ): SalesReclamationItemCollectionResponseTransfer {
        if ($salesReclamationItemCollectionDeleteCriteriaTransfer->getSalesOrderItemIds()) {
            $this->salesReclamationEntityManager->deleteSalesReclamationItemsBySalesOrderItemIds(
                $salesReclamationItemCollectionDeleteCriteriaTransfer->getSalesOrderItemIds(),
            );
        }

        return new SalesReclamationItemCollectionResponseTransfer();
    }
}
