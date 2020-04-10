<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelStorage\Business;

use Generated\Shared\Transfer\FilterTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductLabelStorage\Business\ProductLabelStorageBusinessFactory getFactory()
 * @method \Spryker\Zed\ProductLabelStorage\Persistence\ProductLabelStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ProductLabelStorage\Persistence\ProductLabelStorageRepositoryInterface getRepository()
 */
class ProductLabelStorageFacade extends AbstractFacade implements ProductLabelStorageFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\ProductLabelStorage\Business\ProductLabelStorageFacade::writeProductLabelDictionaryStorageCollection()} instead.
     *
     * @return void
     */
    public function publishLabelDictionary()
    {
        $this->getFactory()->createProductLabelDictionaryStorageWriter()->publish();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return void
     */
    public function writeProductLabelDictionaryStorageCollection(): void
    {
        $this->getFactory()
            ->createProductLabelDictionaryStorageWriter()
            ->writeProductLabelDictionaryStorageCollection();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\ProductLabelStorage\Business\ProductLabelStorageFacade::deleteProductLabelDictionaryStorageCollection()} instead.
     *
     * @return void
     */
    public function unpublishLabelDictionary()
    {
        $this->getFactory()->createProductLabelDictionaryStorageDeleter()->unpublish();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return void
     */
    public function deleteProductLabelDictionaryStorageCollection(): void
    {
        $this->getFactory()
            ->createProductLabelDictionaryStorageDeleter()
            ->deleteProductLabelDictionaryStorageCollection();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\ProductLabelStorage\Business\ProductLabelStorageFacade::writeProductAbstractLabelStorageCollectionByProductAbstractLabelEvents()}
     *              or {@link \Spryker\Zed\ProductLabelStorage\Business\ProductLabelStorageFacade::writeProductAbstractLabelStorageCollectionByProductLabelProductAbstractEvents()} instead.
     *
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    public function publishProductLabel(array $productAbstractIds)
    {
        $this->getFactory()->createProductAbstractLabelStorageWriter()->publish($productAbstractIds);
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
    public function writeProductAbstractLabelStorageCollectionByProductAbstractLabelEvents(array $eventTransfers): void
    {
        $this->getFactory()
            ->createProductAbstractLabelStorageWriter()
            ->writeProductAbstractLabelStorageCollectionByProductAbstractLabelEvents($eventTransfers);
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
    public function writeProductAbstractLabelStorageCollectionByProductLabelProductAbstractEvents(array $eventTransfers): void
    {
        $this->getFactory()
            ->createProductAbstractLabelStorageWriter()
            ->writeProductAbstractLabelStorageCollectionByProductLabelProductAbstractEvents($eventTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    public function unpublishProductLabel(array $productAbstractIds)
    {
        $this->getFactory()->createProductAbstractLabelStorageDeleter()->unpublish($productAbstractIds);
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
    public function getProductAbstractLabelStorageDataTransfersByIds(FilterTransfer $filterTransfer, array $ids): array
    {
        return $this->getRepository()->getProductAbstractLabelStorageDataTransfersByIds($filterTransfer, $ids);
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
    public function getProductLabelDictionaryStorageDataTransfersByIds(FilterTransfer $filterTransfer, array $ids): array
    {
        return $this->getRepository()->getProductLabelDictionaryStorageDataTransfersByIds($filterTransfer, $ids);
    }
}
