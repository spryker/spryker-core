<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePointStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ServicePointStorage\Business\ServicePointStorageBusinessFactory getFactory()
 * @method \Spryker\Zed\ServicePointStorage\Persistence\ServicePointStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\ServicePointStorage\Persistence\ServicePointStorageEntityManagerInterface getEntityManager()
 */
class ServicePointStorageFacade extends AbstractFacade implements ServicePointStorageFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeServicePointStorageCollectionByServicePointEvents(array $eventEntityTransfers): void
    {
        $this->getFactory()
            ->createServicePointStorageWriter()
            ->writeServicePointStorageCollectionByServicePointEvents($eventEntityTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeServicePointStorageCollectionByServicePointAddressEvents(array $eventEntityTransfers): void
    {
        $this->getFactory()
            ->createServicePointStorageWriter()
            ->writeServicePointStorageCollectionByServicePointAddressEvents($eventEntityTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeServicePointStorageCollectionByServicePointStoreEvents(array $eventEntityTransfers): void
    {
        $this->getFactory()
            ->createServicePointStorageWriter()
            ->writeServicePointStorageCollectionByServicePointStoreEvents($eventEntityTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeServicePointStorageCollectionByServiceEvents(array $eventEntityTransfers): void
    {
        $this->getFactory()
            ->createServicePointStorageWriter()
            ->writeServicePointStorageCollectionByServiceEvents($eventEntityTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeServiceTypeStorageCollectionByServiceTypeEvents(array $eventEntityTransfers): void
    {
        $this->getFactory()
            ->createServiceTypeStorageWriter()
            ->writeServiceTypeStorageCollectionByServiceTypeEvents($eventEntityTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $offset
     * @param int $limit
     * @param list<int> $servicePointIds
     *
     * @return list<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function getServicePointStorageSynchronizationDataTransfers(int $offset, int $limit, array $servicePointIds = []): array
    {
        return $this->getRepository()
            ->getServicePointStorageSynchronizationDataTransfers($offset, $limit, $servicePointIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $offset
     * @param int $limit
     * @param list<int> $serviceTypeIds
     *
     * @return list<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function getServiceTypeStorageSynchronizationDataTransfers(int $offset, int $limit, array $serviceTypeIds = []): array
    {
        return $this->getRepository()
            ->getServiceTypeStorageSynchronizationDataTransfers($offset, $limit, $serviceTypeIds);
    }
}
