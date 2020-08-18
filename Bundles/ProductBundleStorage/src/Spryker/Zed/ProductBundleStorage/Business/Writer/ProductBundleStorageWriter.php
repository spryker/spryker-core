<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundleStorage\Business\Writer;

use Generated\Shared\Transfer\ProductBundleCriteriaFilterTransfer;
use Generated\Shared\Transfer\ProductBundleStorageTransfer;
use Orm\Zed\ProductBundle\Persistence\Map\SpyProductBundleTableMap;
use Spryker\Zed\ProductBundleStorage\Dependency\Facade\ProductBundleStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\ProductBundleStorage\Dependency\Facade\ProductBundleStorageToProductBundleFacadeInterface;
use Spryker\Zed\ProductBundleStorage\Persistence\ProductBundleStorageEntityManagerInterface;

class ProductBundleStorageWriter implements ProductBundleStorageWriterInterface
{
    /**
     * @uses \Orm\Zed\Product\Persistence\Map\SpyProductTableMap::COL_IS_ACTIVE
     */
    protected const COL_PRODUCT_CONCRETE_IS_ACTIVE = 'spy_product.is_active';

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
    public function writeCollectionByProductBundlePublishEvents(array $eventTransfers): void
    {
        $productConcreteBundleIds = $this->eventBehaviorFacade->getEventTransferIds($eventTransfers);
        $productConcreteBundleIds = array_unique(array_filter($productConcreteBundleIds));
        if (!$productConcreteBundleIds) {
            return;
        }

        $this->writeCollection($productConcreteBundleIds);
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByProductBundleEvents(array $eventTransfers): void
    {
        $productConcreteBundleIds = $this->eventBehaviorFacade
            ->getEventTransferForeignKeys($eventTransfers, SpyProductBundleTableMap::COL_FK_PRODUCT);
        $productConcreteBundleIds = array_unique(array_filter($productConcreteBundleIds));
        if (!$productConcreteBundleIds) {
            return;
        }

        $this->writeCollection($productConcreteBundleIds);
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByProductEvents(array $eventTransfers): void
    {
        $eventTransfers = $this->eventBehaviorFacade
            ->getEventTransfersByModifiedColumns($eventTransfers, [static::COL_PRODUCT_CONCRETE_IS_ACTIVE]);
        $productConcreteIds = $this->eventBehaviorFacade->getEventTransferIds($eventTransfers);
        $productConcreteIds = array_unique(array_filter($productConcreteIds));
        if (!$productConcreteIds) {
            return;
        }

        $productConcreteBundleIds = array_merge(
            $this->getProductConcreteBundleIds($productConcreteIds),
            $productConcreteIds
        );

        $this->writeCollection($productConcreteBundleIds);
    }

    /**
     * @param int[] $productConcreteBundleIds
     *
     * @return void
     */
    protected function writeCollection(array $productConcreteBundleIds): void
    {
        $productBundleCriteriaFilterTransfer = (new ProductBundleCriteriaFilterTransfer())
            ->setProductConcreteIds($productConcreteBundleIds)
            ->setApplyGrouped(true)
            ->setIsProductConcreteActive(true)
            ->setIsBundledProductActive(true);

        $productBundleCollectionTransfer = $this->productBundleFacade
            ->getProductBundleCollectionByCriteriaFilter($productBundleCriteriaFilterTransfer);

        $savedProductConcreteBundleIds = [];

        foreach ($productBundleCollectionTransfer->getProductBundles() as $productBundleTransfer) {
            $productBundleStorageTransfer = (new ProductBundleStorageTransfer())
                ->fromArray($productBundleTransfer->modifiedToArray(), true);
            $this->productBundleStorageEntityManager->saveProductBundleStorage($productBundleStorageTransfer);
            $savedProductConcreteBundleIds[] = $productBundleStorageTransfer->getIdProductConcreteBundle();
        }

        $this->productBundleStorageEntityManager
            ->deleteProductBundleStorageEntities(array_diff($productConcreteBundleIds, $savedProductConcreteBundleIds));
    }

    /**
     * @param int[] $bundledProductIds
     *
     * @return int[]
     */
    protected function getProductConcreteBundleIds(array $bundledProductIds): array
    {
        $productConcreteBundleIds = [];
        $productBundleCriteriaFilterTransfer = (new ProductBundleCriteriaFilterTransfer())
            ->setBundledProductIds($bundledProductIds)
            ->setApplyGrouped(true);
        $productBundleCollectionTransfer = $this->productBundleFacade
            ->getProductBundleCollectionByCriteriaFilter($productBundleCriteriaFilterTransfer);
        foreach ($productBundleCollectionTransfer->getProductBundles() as $productBundleTransfer) {
            $productConcreteBundleIds[] = $productBundleTransfer->getIdProductConcreteBundle();
        }

        return $productConcreteBundleIds;
    }
}
