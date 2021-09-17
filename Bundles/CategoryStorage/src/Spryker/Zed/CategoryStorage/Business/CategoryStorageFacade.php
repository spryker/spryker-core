<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\CategoryStorage\Persistence\CategoryStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\CategoryStorage\Business\CategoryStorageBusinessFactory getFactory()
 * @method \Spryker\Zed\CategoryStorage\Persistence\CategoryStorageRepositoryInterface getRepository()
 */
class CategoryStorageFacade extends AbstractFacade implements CategoryStorageFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Will be removed in the next major without replacement.
     *
     * @param array<int> $categoryNodeIds
     *
     * @return void
     */
    public function publish(array $categoryNodeIds): void
    {
        $this->getFactory()->createCategoryNodeStorageWriter()->writeCategoryNodeStorageCollection($categoryNodeIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Will be removed in the next major without replacement.
     *
     * @param array<int> $categoryNodeIds
     *
     * @return void
     */
    public function unpublish(array $categoryNodeIds): void
    {
        $this->getFactory()->createCategoryNodeStorageDeleter()->deleteCategoryNodeStorageCollection($categoryNodeIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\CategoryStorage\Business\CategoryStorageFacade::writeCategoryTreeStorageCollection} instead.
     *
     * @return void
     */
    public function publishCategoryTree(): void
    {
        $this->getFactory()->createCategoryTreeStorageWriter()->writeCategoryTreeStorageCollection();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\CategoryStorage\Business\CategoryStorageFacade::deleteCategoryTreeStorageCollection} instead.
     *
     * @return void
     */
    public function unpublishCategoryTree(): void
    {
        $this->getEntityManager()->deleteCategoryTreeStorageCollection();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeCategoryNodeStorageCollectionByCategoryStoreEvents(array $eventEntityTransfers): void
    {
        $this->getFactory()
            ->createCategoryNodeStorageByCategoryStoreEventsWriter()
            ->writeCategoryNodeStorageCollectionByCategoryStoreEvents($eventEntityTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeCategoryNodeStorageCollectionByCategoryStorePublishEvents(array $eventEntityTransfers): void
    {
        $this->getFactory()
            ->createCategoryNodeStorageByCategoryStoreEventsWriter()
            ->writeCategoryNodeStorageCollectionByCategoryStorePublishEvents($eventEntityTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return void
     */
    public function writeCategoryTreeStorageCollection(): void
    {
        $this->getFactory()->createCategoryTreeStorageWriter()->writeCategoryTreeStorageCollection();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return void
     */
    public function deleteCategoryTreeStorageCollection(): void
    {
        $this->getEntityManager()->deleteCategoryTreeStorageCollection();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $offset
     * @param int $limit
     * @param array<int> $categoryNodeIds
     *
     * @return array<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function getCategoryNodeStorageSynchronizationDataTransfersByCategoryNodeIds(int $offset, int $limit, array $categoryNodeIds): array
    {
        return $this->getRepository()
            ->getCategoryNodeStorageSynchronizationDataTransfersByCategoryNodeIds($offset, $limit, $categoryNodeIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $offset
     * @param int $limit
     * @param array<int> $categoryTreeStorageIds
     *
     * @return array<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function getCategoryTreeStorageSynchronizationDataTransfersByCategoryTreeStorageIds(int $offset, int $limit, array $categoryTreeStorageIds): array
    {
        return $this->getRepository()
            ->getCategoryTreeStorageSynchronizationDataTransfersByCategoryTreeStorageIds($offset, $limit, $categoryTreeStorageIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeCategoryNodeStorageCollectionByCategoryAttributeEvents(array $eventEntityTransfers): void
    {
        $this->getFactory()
            ->createCategoryNodeStorageByCategoryAttributeEventsWriter()
            ->writeCategoryNodeStorageCollectionByCategoryAttributeEvents($eventEntityTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeCategoryNodeStorageCollectionByCategoryEvents(array $eventEntityTransfers): void
    {
        $this->getFactory()
            ->createCategoryNodeStorageByCategoryEventsWriter()
            ->writeCategoryNodeStorageCollectionByCategoryEvents($eventEntityTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeCategoryNodeStorageCollectionByCategoryTemplateEvents(array $eventEntityTransfers): void
    {
        $this->getFactory()
            ->createCategoryNodeStorageByCategoryTemplateEventsWriter()
            ->writeCategoryNodeStorageCollectionByCategoryTemplateEvents($eventEntityTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeCategoryNodeStorageCollectionByParentCategoryEvents(array $eventEntityTransfers): void
    {
        $this->getFactory()
            ->createCategoryNodeStorageWriter()
            ->writeCategoryNodeStorageCollectionByParentCategoryEvents($eventEntityTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeCategoryNodeStorageCollectionByCategoryNodeEvents(array $eventEntityTransfers): void
    {
        $this->getFactory()
            ->createCategoryNodeStorageWriter()
            ->writeCategoryNodeStorageCollectionByCategoryNodeEvents($eventEntityTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function deleteCategoryNodeStorageCollectionByCategoryAttributeEvents(array $eventEntityTransfers): void
    {
        $this->getFactory()
            ->createCategoryNodeStorageByCategoryAttributeEventsDeleter()
            ->deleteCategoryNodeStorageCollectionByCategoryAttributeEvents($eventEntityTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function deleteCategoryNodeStorageCollectionByCategoryTemplateEvents(array $eventEntityTransfers): void
    {
        $this->getFactory()
            ->createCategoryNodeStorageByCategoryTemplateEventsDeleter()
            ->deleteCategoryNodeStorageCollectionByCategoryTemplateEvents($eventEntityTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function deleteCategoryNodeStorageCollectionByCategoryEvents(array $eventEntityTransfers): void
    {
        $this->getFactory()
            ->createCategoryNodeStorageByCategoryEventsDeleter()
            ->deleteCategoryNodeStorageCollectionByCategoryEvents($eventEntityTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function deleteCategoryNodeStorageCollectionByCategoryNodeEvents(array $eventEntityTransfers): void
    {
        $this->getFactory()
            ->createCategoryNodeStorageDeleter()
            ->deleteCategoryNodeStorageCollectionByCategoryNodeEvents($eventEntityTransfers);
    }
}
