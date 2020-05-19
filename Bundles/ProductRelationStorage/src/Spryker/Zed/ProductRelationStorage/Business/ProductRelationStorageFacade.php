<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelationStorage\Business;

use Generated\Shared\Transfer\FilterTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductRelationStorage\Business\ProductRelationStorageBusinessFactory getFactory()
 * @method \Spryker\Zed\ProductRelationStorage\Persistence\ProductRelationStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductRelationStorage\Persistence\ProductRelationStorageEntityManagerInterface getEntityManager()
 */
class ProductRelationStorageFacade extends AbstractFacade implements ProductRelationStorageFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use \Spryker\Zed\ProductRelationStorage\Business\ProductRelationStorageFacade::writeCollectionByProductRelationEvents(),
     * \Spryker\Zed\ProductRelationStorage\Business\ProductRelationStorageFacade::writeCollectionByProductRelationStoreEvents(),
     * \Spryker\Zed\ProductRelationStorage\Business\ProductRelationStorageFacade::writeCollectionByProductRelationPublishingEvents(),
     * \Spryker\Zed\ProductRelationStorage\Business\ProductRelationStorageFacade::writeCollectionByProductRelationProductAbstractEvents() instead.
     *
     * @see \Spryker\Zed\ProductRelationStorage\Business\ProductRelationStorageFacade::writeCollectionByProductRelationEvents()
     * @see \Spryker\Zed\ProductRelationStorage\Business\ProductRelationStorageFacade::writeCollectionByProductRelationStoreEvents()
     * @see \Spryker\Zed\ProductRelationStorage\Business\ProductRelationStorageFacade::writeCollectionByProductRelationPublishingEvents()
     * @see \Spryker\Zed\ProductRelationStorage\Business\ProductRelationStorageFacade::writeCollectionByProductRelationProductAbstractEvents()
     *
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    public function publish(array $productAbstractIds)
    {
        $this->getFactory()->createProductRelationStorageWriter()->publish($productAbstractIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use \Spryker\Zed\ProductRelationStorage\Business\ProductRelationStorageFacade::writeCollectionByProductRelationEvents(),
     * \Spryker\Zed\ProductRelationStorage\Business\ProductRelationStorageFacade::writeCollectionByProductRelationStoreEvents(),
     * \Spryker\Zed\ProductRelationStorage\Business\ProductRelationStorageFacade::writeCollectionByProductRelationPublishingEvents(),
     * \Spryker\Zed\ProductRelationStorage\Business\ProductRelationStorageFacade::writeCollectionByProductRelationProductAbstractEvents() instead.
     *
     * @see \Spryker\Zed\ProductRelationStorage\Business\ProductRelationStorageFacade::writeCollectionByProductRelationEvents()
     * @see \Spryker\Zed\ProductRelationStorage\Business\ProductRelationStorageFacade::writeCollectionByProductRelationStoreEvents()
     * @see \Spryker\Zed\ProductRelationStorage\Business\ProductRelationStorageFacade::writeCollectionByProductRelationPublishingEvents()
     * @see \Spryker\Zed\ProductRelationStorage\Business\ProductRelationStorageFacade::writeCollectionByProductRelationProductAbstractEvents()
     *
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    public function unpublish(array $productAbstractIds)
    {
        $this->getFactory()->createProductRelationStorageWriter()->unpublish($productAbstractIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByProductRelationEvents(array $eventTransfers): void
    {
        $this->getFactory()
            ->createProductRelationStorageWriter()
            ->writeProductRelationStorageCollectionByProductRelationEvents($eventTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByProductRelationStoreEvents(array $eventTransfers): void
    {
        $this->getFactory()
            ->createProductRelationStorageWriter()
            ->writeProductRelationStorageCollectionByProductRelationStoreEvents($eventTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByProductRelationPublishingEvents(array $eventTransfers): void
    {
        $this->getFactory()
            ->createProductRelationStorageWriter()
            ->writeProductRelationStorageCollectionByProductRelationPublishingEvents($eventTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByProductRelationProductAbstractEvents(array $eventTransfers): void
    {
        $this->getFactory()
            ->createProductRelationStorageWriter()
            ->writeProductRelationStorageCollectionByProductRelationProductAbstractEvents($eventTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param int[] $ids
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function findProductRelationStorageDataTransfersByIds(FilterTransfer $filterTransfer, array $ids): array
    {
        return $this->getRepository()->findProductRelationStorageDataTransferByIds($filterTransfer, $ids);
    }
}
