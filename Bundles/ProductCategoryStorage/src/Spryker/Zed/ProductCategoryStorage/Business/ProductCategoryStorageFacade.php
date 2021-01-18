<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductCategoryStorage\Business\ProductCategoryStorageBusinessFactory getFactory()
 * @method \Spryker\Zed\ProductCategoryStorage\Persistence\ProductCategoryStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductCategoryStorage\Persistence\ProductCategoryStorageEntityManagerInterface getEntityManager()
 */
class ProductCategoryStorageFacade extends AbstractFacade implements ProductCategoryStorageFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    public function publish(array $productAbstractIds)
    {
        $this->getFactory()->createProductCategoryStorageWriter()->publish($productAbstractIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    public function unpublish(array $productAbstractIds)
    {
        $this->getFactory()->createProductCategoryStorageDeleter()->unpublish($productAbstractIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int[] $categoryIds
     *
     * @return int[]
     */
    public function getRelatedCategoryIds(array $categoryIds)
    {
        return $this->getFactory()->createProductAbstractReader()->getRelatedCategoryIds($categoryIds);
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
    public function writeCollectionByCategoryStoreEvents(array $eventEntityTransfers): void
    {
        $this->getFactory()
            ->createProductCategoryStorageWriter()
            ->writeCollectionByCategoryStoreEvents($eventEntityTransfers);
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
    public function writeCollectionByCategoryStorePublishingEvents(array $eventEntityTransfers): void
    {
        $this->getFactory()
            ->createProductCategoryStorageWriter()
            ->writeCollectionByCategoryStorePublishingEvents($eventEntityTransfers);
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
    public function deleteCollectionByCategoryStoreEvents(array $eventEntityTransfers): void
    {
        $this->getFactory()
            ->createProductCategoryStorageDeleter()
            ->deleteCollectionByCategoryStoreEvents($eventEntityTransfers);
    }
}
