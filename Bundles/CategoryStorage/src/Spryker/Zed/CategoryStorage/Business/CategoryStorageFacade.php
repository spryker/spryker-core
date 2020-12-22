<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
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
        $this->getFactory()->createCategoryNodeStorage()->publish($categoryNodeIds);
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
        $this->getFactory()->createCategoryNodeStorage()->unpublish($categoryNodeIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return void
     */
    public function publishCategoryTree(): void
    {
        $this->getFactory()->createCategoryTreeStorage()->publish();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return void
     */
    public function unpublishCategoryTree(): void
    {
        $this->getFactory()->createCategoryTreeStorage()->unpublish();
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
    public function writeCategoryNodeStorageCollectionByCategoryEvents(array $eventEntityTransfers): void
    {
        $this->getFactory()
            ->createCategoryNodeStorage()
            ->writeCategoryNodeStorageCollectionByCategoryEvents($eventEntityTransfers);
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
        $this->getFactory()->createCategoryTreeStorage()->writeCategoryTreeStorageCollection();
    }
}
