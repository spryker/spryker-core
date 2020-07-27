<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
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
    public function writeCollectionByProductBundlePublishEvents(array $eventTransfers): void
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
    public function writeCollectionByProductBundleEvents(array $eventTransfers): void
    {
        $productConcreteIds = $this->eventBehaviorFacade->getEventTransferForeignKeys($eventTransfers, SpyProductBundleTableMap::COL_FK_PRODUCT);
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
    public function writeCollectionByProductEvents(array $eventTransfers): void
    {
        $bundledProductIds = $this->eventBehaviorFacade->getEventTransferIds($eventTransfers);
        $bundledProductIds = array_unique(array_filter($bundledProductIds));
        if (!$bundledProductIds) {
            return;
        }

        $this->writeCollection($this->getProductConcreteIds($bundledProductIds));
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
            ->setApplyGrouped(true);

        $productBundleCollectionTransfer = $this->productBundleFacade
            ->getProductBundleCollectionByCriteriaFilter($productBundleCriteriaFilterTransfer);

        foreach ($productBundleCollectionTransfer->getProductBundles() as $productBundleTransfer) {
            $this->removeInactiveBundledProducts($productBundleTransfer);
        }

        foreach ($productBundleCollectionTransfer->getProductBundles() as $productBundleTransfer) {
            $productBundleStorageTransfer = (new ProductBundleStorageTransfer())->fromArray($productBundleTransfer->modifiedToArray(), true);
            $this->productBundleStorageEntityManager->saveProductBundleStorage($productBundleStorageTransfer);
        }
    }

    /**
     * @param int[] $bundledProductIds
     *
     * @return int[]
     */
    protected function getProductConcreteIds(array $bundledProductIds): array
    {
        $productConcreteIds = [];
        $productBundleCriteriaFilterTransfer = (new ProductBundleCriteriaFilterTransfer())
            ->setBundledProductIds($bundledProductIds)
            ->setApplyGrouped(true);
        $productBundleCollectionTransfer = $this->productBundleFacade
            ->getProductBundleCollectionByCriteriaFilter($productBundleCriteriaFilterTransfer);
        foreach ($productBundleCollectionTransfer->getProductBundles() as $productBundleTransfer) {
            $productConcreteIds[] = $productBundleTransfer->getIdProductConcreteBundle();
        }

        return $productConcreteIds;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductBundleTransfer $productBundleTransfer
     *
     * @return void
     */
    protected function removeInactiveBundledProducts(ProductBundleTransfer $productBundleTransfer): void
    {
        foreach ($productBundleTransfer->getBundledProducts() as $key => $productForBundleTransfer) {
            if (!$productForBundleTransfer->getIsActive()) {
                $productBundleTransfer->getBundledProducts()->offsetUnset($key);
            }
        }
    }
}
