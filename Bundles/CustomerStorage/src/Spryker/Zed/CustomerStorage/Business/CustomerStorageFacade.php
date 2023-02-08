<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerStorage\Business;

use Generated\Shared\Transfer\PaginationTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\CustomerStorage\Business\CustomerStorageBusinessFactory getFactory()
 * @method \Spryker\Zed\CustomerStorage\Persistence\CustomerStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\CustomerStorage\Persistence\CustomerStorageRepositoryInterface getRepository()
 */
class CustomerStorageFacade extends AbstractFacade implements CustomerStorageFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeCustomerInvalidatedStorageCollectionByCustomerEvents(array $eventEntityTransfers): void
    {
        $this->getFactory()->createCustomersStorageWriter()
            ->writeCustomerInvalidatedStorageCollectionByCustomerEvents($eventEntityTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return void
     */
    public function deleteExpiredCustomerInvalidatedStorage(): void
    {
        $this->getFactory()->createCustomersStorageDeleter()->deleteExpiredCustomerInvalidatedStorage();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     * @param array<int, int> $customerIds
     *
     * @return array<int, \Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function getSynchronizationDataTransferCollection(
        PaginationTransfer $paginationTransfer,
        array $customerIds
    ): array {
        return $this->getFactory()
            ->createCustomersStorageReader()
            ->getSynchronizationDataTransferCollection($paginationTransfer, $customerIds);
    }
}
