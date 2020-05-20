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
     * @deprecated Use {@link writeProductLabelDictionaryStorageCollection()} instead.
     *
     * @return void
     */
    public function publishLabelDictionary()
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
     * @deprecated Use {@link \Spryker\Zed\ProductLabelStorage\Business\ProductLabelStorageFacadeInterface::deleteProductLabelDictionaryStorageCollection()} instead.
     *
     * @return void
     */
    public function unpublishLabelDictionary()
    {
        $this->getEntityManager()->deleteAllProductLabelDictionaryStorageEntities();
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
        $this->getEntityManager()->deleteAllProductLabelDictionaryStorageEntities();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\ProductLabelStorage\Business\ProductLabelStorageFacadeInterface::writeProductAbstractLabelStorageCollectionByProductAbstractLabelEvents()}
     *   or {@link \Spryker\Zed\ProductLabelStorage\Business\ProductLabelStorageFacadeInterface::writeProductAbstractLabelStorageCollectionByProductLabelProductAbstractEvents()} instead.
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
     * @deprecated Use {@link writeProductAbstractLabelStorageCollectionByProductAbstractLabelEvents()}
     *  or {@link writeProductAbstractLabelStorageCollectionByProductLabelProductAbstractEvents()} instead.
     *
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    public function unpublishProductLabel(array $productAbstractIds)
    {
        $this->getFactory()->createProductAbstractLabelStorageWriter()->unpublish($productAbstractIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param int[] $productAbstractLabelStorageIds
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function getProductAbstractLabelStorageDataTransfersByIds(
        FilterTransfer $filterTransfer,
        array $productAbstractLabelStorageIds
    ): array {
        return $this->getRepository()
            ->getProductAbstractLabelStorageDataTransfersByIds($filterTransfer, $productAbstractLabelStorageIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param int[] $productLabelDictionaryStorageIds
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function getProductLabelDictionaryStorageDataTransfersByIds(
        FilterTransfer $filterTransfer,
        array $productLabelDictionaryStorageIds
    ): array {
        return $this->getRepository()
            ->getProductLabelDictionaryStorageDataTransfersByIds($filterTransfer, $productLabelDictionaryStorageIds);
    }
}
