<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundleStorage\Business\Writer;

use Generated\Shared\Transfer\ProductBundleCriteriaFilterTransfer;
use Generated\Shared\Transfer\ProductBundleStorageTransfer;
use Generated\Shared\Transfer\ProductBundleTransfer;
use Orm\Zed\ProductBundle\Persistence\Map\SpyProductBundleTableMap;
use Spryker\Zed\ProductBundleStorage\Dependency\Facade\ProductBundleStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\ProductBundleStorage\Dependency\Facade\ProductBundleStorageToProductBundleFacadeInterface;
use Spryker\Zed\ProductBundleStorage\Persistence\ProductBundleStorageEntityManagerInterface;

class ProductBundleStorageWriter implements ProductBundleStorageWriterInterface
{
    /**
     * @var \Spryker\Zed\ProductBundleStorage\Dependency\Facade\ProductBundleStorageToEventBehaviorFacadeInterface
     */
    protected $eventBehaviorFacade;

    /**
     * @var \Spryker\Zed\ProductBundleStorage\Dependency\Facade\ProductBundleStorageToProductBundleFacadeInterface
     */
    protected $productBundleFacade;

    /**
     * @var \Spryker\Zed\ProductBundleStorage\Persistence\ProductBundleStorageEntityManagerInterface
     */
    protected $productBundleStorageEntityManager;

    /**
     * @param \Spryker\Zed\ProductBundleStorage\Dependency\Facade\ProductBundleStorageToEventBehaviorFacadeInterface $eventBehaviorFacade
     * @param \Spryker\Zed\ProductBundleStorage\Dependency\Facade\ProductBundleStorageToProductBundleFacadeInterface $productBundleFacade
     * @param \Spryker\Zed\ProductBundleStorage\Persistence\ProductBundleStorageEntityManagerInterface $productBundleStorageEntityManager
     */
    public function __construct(
        ProductBundleStorageToEventBehaviorFacadeInterface $eventBehaviorFacade,
        ProductBundleStorageToProductBundleFacadeInterface $productBundleFacade,
        ProductBundleStorageEntityManagerInterface $productBundleStorageEntityManager
    ) {
        $this->eventBehaviorFacade = $eventBehaviorFacade;
        $this->productBundleFacade = $productBundleFacade;
        $this->productBundleStorageEntityManager = $productBundleStorageEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByProductConcreteBundleIdsEvents(array $eventTransfers): void
    {
        $productConcreteIds = $this->eventBehaviorFacade->getEventTransferIds($eventTransfers);
        $productConcreteIds = array_unique(array_filter($productConcreteIds));

        if (!$productConcreteIds) {
            return;
        }

        $this->writeCollection($productConcreteIds);
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByProductConcreteIdsEvents(array $eventTransfers): void
    {
        $productConcreteIds = $this->eventBehaviorFacade
            ->getEventTransferForeignKeys($eventTransfers, SpyProductBundleTableMap::COL_FK_PRODUCT);

        $productConcreteIds = array_unique(array_filter($productConcreteIds));

        if (!$productConcreteIds) {
            return;
        }

        $this->writeCollection($productConcreteIds);
    }

    /**
     * @param int[] $productConcreteIds
     *
     * @return void
     */
    protected function writeCollection(array $productConcreteIds): void
    {
        $productBundleCriteriaFilterTransfer = (new ProductBundleCriteriaFilterTransfer())
            ->setProductConcreteIds($productConcreteIds)
            ->setIsGrouped(true);

        $productBundleCollectionTransfer = $this->productBundleFacade
            ->getProductBundleCollectionByCriteriaFilter($productBundleCriteriaFilterTransfer);

        foreach ($productBundleCollectionTransfer->getProductBundles() as $productBundleTransfer) {
            $productBundleStorageTransfer = $this->mapProductBundleTransferToStorageTransfer(
                $productBundleTransfer,
                new ProductBundleStorageTransfer()
            );

            $this->productBundleStorageEntityManager->saveProductBundleStorage($productBundleStorageTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductBundleTransfer $productBundleTransfer
     * @param \Generated\Shared\Transfer\ProductBundleStorageTransfer $productBundleStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ProductBundleStorageTransfer
     */
    protected function mapProductBundleTransferToStorageTransfer(
        ProductBundleTransfer $productBundleTransfer,
        ProductBundleStorageTransfer $productBundleStorageTransfer
    ): ProductBundleStorageTransfer {
        $productBundleStorageTransfer = $productBundleStorageTransfer->fromArray($productBundleTransfer->modifiedToArray(), true);

        return $productBundleStorageTransfer;
    }
}
