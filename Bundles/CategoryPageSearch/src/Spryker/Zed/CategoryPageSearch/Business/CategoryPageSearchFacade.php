<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryPageSearch\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\CategoryPageSearch\Business\CategoryPageSearchBusinessFactory getFactory()
 * @method \Spryker\Zed\CategoryPageSearch\Persistence\CategoryPageSearchEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\CategoryPageSearch\Persistence\CategoryPageSearchRepositoryInterface getRepository()
 */
class CategoryPageSearchFacade extends AbstractFacade implements CategoryPageSearchFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $categoryNodeIds
     *
     * @return void
     */
    public function publish(array $categoryNodeIds)
    {
        $this->getFactory()->createCategoryNodePageSearchWriter()->writeCategoryNodePageSearchCollection($categoryNodeIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $categoryNodeIds
     *
     * @return void
     */
    public function unpublish(array $categoryNodeIds)
    {
        $this->getFactory()->createCategoryNodePageSearchDeleter()->deleteCategoryNodePageSearchCollection($categoryNodeIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     *
     * @return void
     */
    public function writeCategoryNodePageSearchCollectionByCategoryStoreEvents(array $eventEntityTransfers): void
    {
        $this->getFactory()
            ->createCategoryNodePageSearchWriter()
            ->writeCategoryNodePageSearchCollectionByCategoryStoreEvents($eventEntityTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     *
     * @return void
     */
    public function writeCategoryNodePageSearchCollectionByCategoryStorePublishEvents(array $eventEntityTransfers): void
    {
        $this->getFactory()
            ->createCategoryNodePageSearchWriter()
            ->writeCategoryNodePageSearchCollectionByCategoryStorePublishEvents($eventEntityTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     *
     * @return void
     */
    public function writeCategoryNodePageSearchCollectionByCategoryAttributeEvents(array $eventEntityTransfers): void
    {
        $this->getFactory()
            ->createCategoryNodePageSearchWriter()
            ->writeCategoryNodePageSearchCollectionByCategoryAttributeEvents($eventEntityTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     *
     * @return void
     */
    public function writeCategoryNodePageSearchCollectionByCategoryEvents(array $eventEntityTransfers): void
    {
        $this->getFactory()
            ->createCategoryNodePageSearchWriter()
            ->writeCategoryNodePageSearchCollectionByCategoryEvents($eventEntityTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     *
     * @return void
     */
    public function writeCategoryNodePageSearchCollectionByCategoryTemplateEvents(array $eventEntityTransfers): void
    {
        $this->getFactory()
            ->createCategoryNodePageSearchWriter()
            ->writeCategoryNodePageSearchCollectionByCategoryTemplateEvents($eventEntityTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     *
     * @return void
     */
    public function writeCategoryNodePageSearchCollectionByCategoryNodeEvents(array $eventEntityTransfers): void
    {
        $this->getFactory()
            ->createCategoryNodePageSearchWriter()
            ->writeCategoryNodePageSearchCollectionByCategoryNodeEvents($eventEntityTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     *
     * @return void
     */
    public function deleteCategoryNodePageSearchCollectionByCategoryAttributeEvents(array $eventEntityTransfers): void
    {
        $this->getFactory()
            ->createCategoryNodePageSearchDeleter()
            ->deleteCategoryNodePageSearchCollectionByCategoryAttributeEvents($eventEntityTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     *
     * @return void
     */
    public function deleteCategoryNodePageSearchCollectionByCategoryEvents(array $eventEntityTransfers): void
    {
        $this->getFactory()
            ->createCategoryNodePageSearchDeleter()
            ->deleteCategoryNodePageSearchCollectionByCategoryEvents($eventEntityTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     *
     * @return void
     */
    public function deleteCategoryNodePageSearchCollectionByCategoryTemplateEvents(array $eventEntityTransfers): void
    {
        $this->getFactory()
            ->createCategoryNodePageSearchDeleter()
            ->deleteCategoryNodePageSearchCollectionByCategoryTemplateEvents($eventEntityTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     *
     * @return void
     */
    public function deleteCategoryNodePageSearchCollectionByCategoryNodeEvents(array $eventEntityTransfers): void
    {
        $this->getFactory()
            ->createCategoryNodePageSearchDeleter()
            ->deleteCategoryNodePageSearchCollectionByCategoryNodeEvents($eventEntityTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $offset
     * @param int $limit
     * @param int[] $categoryNodeIds
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function findSynchronizationDataTransfersByIds(int $offset, int $limit, array $categoryNodeIds): array
    {
        return $this->getRepository()
            ->findSynchronizationDataTransfersByIds($offset, $limit, $categoryNodeIds);
    }
}
