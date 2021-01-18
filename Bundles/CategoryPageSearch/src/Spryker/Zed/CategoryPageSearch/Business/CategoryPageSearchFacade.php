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
}
