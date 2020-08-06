<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundleStorage\Business;

use Generated\Shared\Transfer\FilterTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductBundleStorage\Business\ProductBundleStorageBusinessFactory getFactory()
 * @method \Spryker\Zed\ProductBundleStorage\Persistence\ProductBundleStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductBundleStorage\Persistence\ProductBundleStorageEntityManagerInterface getEntityManager()
 */
class ProductBundleStorageFacade extends AbstractFacade implements ProductBundleStorageFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByProductBundlePublishEvents(array $eventTransfers): void
    {
        $this->getFactory()
            ->createProductBundleStorageWriter()
            ->writeCollectionByProductBundlePublishEvents($eventTransfers);
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
    public function writeCollectionByProductBundleEvents(array $eventTransfers): void
    {
        $this->getFactory()
            ->createProductBundleStorageWriter()
            ->writeCollectionByProductBundleEvents($eventTransfers);
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
    public function writeCollectionByProductEvents(array $eventTransfers): void
    {
        $this->getFactory()
            ->createProductBundleStorageWriter()
            ->writeCollectionByProductEvents($eventTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param int[] $productConcreteIds
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function getPaginatedProductBundleStorageDataTransfers(FilterTransfer $filterTransfer, array $productConcreteIds): array
    {
        return $this->getRepository()->getPaginatedProductBundleStorageDataTransfers($filterTransfer, $productConcreteIds);
    }
}
