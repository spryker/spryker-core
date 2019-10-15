<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerAccessStorage\Communication\Plugin\Synchronization;

use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Shared\CustomerAccessStorage\CustomerAccessStorageConstants;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataRepositoryPluginInterface;

/**
 * @deprecated Use CustomerAccessSynchronizationDataBulkPlugin instead.
 *
 * @method \Spryker\Zed\CustomerAccessStorage\Business\CustomerAccessStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\CustomerAccessStorage\Communication\CustomerAccessStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\CustomerAccessStorage\CustomerAccessStorageConfig getConfig()
 * @method \Spryker\Zed\CustomerAccessStorage\Persistence\CustomerAccessStorageRepositoryInterface getRepository()
 */
class CustomerAccessSynchronizationDataPlugin extends AbstractPlugin implements SynchronizationDataRepositoryPluginInterface
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
        return CustomerAccessStorageConstants::CUSTOMER_ACCESS_RESOURCE_NAME;
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
     * @return array
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
        return CustomerAccessStorageConstants::CUSTOMER_ACCESS_SYNC_STORAGE_QUEUE;
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
        return $this->getFactory()->getConfig()->getSynchronizationPoolName();
    }

    /**
     * @api
     *
     * @param int[] $ids
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function getData(array $ids = [])
    {
        $customerAccessStorageEntities = $this->getRepository()->findAllCustomerAccessStorageEntities();

        $synchronizationDataTransfers = [];

        foreach ($customerAccessStorageEntities as $customerAccessStorageEntity) {
            $synchronizationDataTransfer = new SynchronizationDataTransfer();
            /** @var string $data */
            $data = $customerAccessStorageEntity->getData();
            $synchronizationDataTransfer->setData($data);
            $synchronizationDataTransfer->setKey($customerAccessStorageEntity->getKey());
            $synchronizationDataTransfers[] = $synchronizationDataTransfer;
        }

        return $synchronizationDataTransfers;
    }
}
