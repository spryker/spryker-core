<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePointSearch\Business;

use Generated\Shared\Transfer\FilterTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ServicePointSearch\Business\ServicePointSearchBusinessFactory getFactory()
 * @method \Spryker\Zed\ServicePointSearch\Persistence\ServicePointSearchRepositoryInterface getRepository()
 * @method \Spryker\Zed\ServicePointSearch\Persistence\ServicePointSearchEntityManagerInterface getEntityManager()
 */
class ServicePointSearchFacade extends AbstractFacade implements ServicePointSearchFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByServicePointEvents(array $eventTransfers): void
    {
        $this->getFactory()
            ->createServicePointSearchWriter()
            ->writeCollectionByServicePointEvents($eventTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByServicePointAddressEvents(array $eventTransfers): void
    {
        $this->getFactory()
            ->createServicePointSearchWriter()
            ->writeCollectionByServicePointAddressEvents($eventTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByServicePointStoreEvents(array $eventTransfers): void
    {
        $this->getFactory()
            ->createServicePointSearchWriter()
            ->writeCollectionByServicePointStoreEvents($eventTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function deleteCollectionByServicePointEvents(array $eventTransfers): void
    {
        $this->getFactory()
            ->createServicePointSearchDeleter()
            ->deleteCollectionByServicePointEvents($eventTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param list<int> $servicePointIds
     *
     * @return list<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function getServicePointSynchronizationDataTransfersByIds(FilterTransfer $filterTransfer, array $servicePointIds = []): array
    {
        return $this->getRepository()->getServicePointSynchronizationDataTransfersByIds($filterTransfer, $servicePointIds);
    }
}
