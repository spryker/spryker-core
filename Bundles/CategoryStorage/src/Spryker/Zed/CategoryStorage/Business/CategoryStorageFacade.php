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
 */
class CategoryStorageFacade extends AbstractFacade implements CategoryStorageFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int[] $categoryNodeIds
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
     * @param int[] $categoryNodeIds
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
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     *
     * @return void
     */
    public function writeCategoryNodeStorageCollectionByCategoryStoreEvents(array $eventEntityTransfers): void
    {
        $this->getFactory()
            ->createCategoryNodeStorageWriter()
            ->writeCategoryNodeStorageCollectionByCategoryStoreEvents($eventEntityTransfers);
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
    public function writeCategoryNodeStorageCollectionByCategoryStorePublishEvents(array $eventEntityTransfers): void
    {
        $this->getFactory()
            ->createCategoryNodeStorageWriter()
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
}
