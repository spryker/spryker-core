<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePointStorage\Communication\Plugin\Synchronization;

use Spryker\Shared\ServicePointStorage\ServicePointStorageConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataBulkRepositoryPluginInterface;

/**
 * @method \Spryker\Zed\ServicePointStorage\Business\ServicePointStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ServicePointStorage\Communication\ServicePointStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\ServicePointStorage\ServicePointStorageConfig getConfig()
 */
class ServicePointSynchronizationDataBulkRepositoryPlugin extends AbstractPlugin implements SynchronizationDataBulkRepositoryPluginInterface
{
    /**
     * {@inheritDoc}
     * - Retrieves a collection of service point storage transfers according to provided offset, limit and IDs.
     *
     * @api
     *
     * @param int $offset
     * @param int $limit
     * @param list<int> $ids
     *
     * @return list<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function getData(int $offset, int $limit, array $ids = []): array
    {
        return $this->getFacade()->getServicePointStorageSynchronizationDataTransfers($offset, $limit, $ids);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getResourceName(): string
    {
        return ServicePointStorageConfig::SERVICE_POINT_RESOURCE_NAME;
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
        return true;
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
        return ServicePointStorageConfig::QUEUE_NAME_SYNC_STORAGE_SERVICE_POINT;
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
        return $this->getConfig()->getServicePointStorageSynchronizationPoolName();
    }
}
