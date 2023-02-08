<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerStorage\Communication\Plugin\Synchronization;

use Generated\Shared\Transfer\PaginationTransfer;
use Spryker\Shared\CustomerStorage\CustomerStorageConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataBulkRepositoryPluginInterface;

/**
 * @method \Spryker\Zed\CustomerStorage\Persistence\CustomerStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\CustomerStorage\CustomerStorageConfig getConfig()
 * @method \Spryker\Zed\CustomerStorage\Business\CustomerStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\CustomerStorage\Communication\CustomerStorageCommunicationFactory getFactory()
 */
class CustomerInvalidatedStorageSynchronizationDataPlugin extends AbstractPlugin implements SynchronizationDataBulkRepositoryPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getResourceName(): string
    {
        return CustomerStorageConfig::CUSTOMER_RESOURCE_NAME;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return bool
     */
    public function hasStore(): bool
    {
        return false;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $offset
     * @param int $limit
     * @param array<int, int> $ids
     *
     * @return array<int, \Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function getData(int $offset, int $limit, array $ids = []): array
    {
        $paginationTransfer = (new PaginationTransfer())
            ->setOffset($offset)
            ->setLimit($limit);

        return $this->getFacade()->getSynchronizationDataTransferCollection($paginationTransfer, $ids);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return array<string, string>
     */
    public function getParams(): array
    {
        return [];
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getQueueName(): string
    {
        return CustomerStorageConfig::CUSTOMER_INVALIDATED_SYNC_STORAGE_QUEUE;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string|null
     */
    public function getSynchronizationQueuePoolName(): ?string
    {
        return $this->getFactory()->getConfig()->getCustomerInvalidatedSynchronizationPoolName();
    }
}
