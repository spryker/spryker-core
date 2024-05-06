<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\ProductBundle;

use ArrayObject;
use Generated\Shared\Transfer\ProductAvailabilityCriteriaTransfer;
use Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductForBundleTransfer;
use Spryker\Zed\ProductBundle\Business\ProductBundle\Cache\ProductBundleCacheInterface;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToAvailabilityFacadeInterface;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToStoreFacadeInterface;
use Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface;
use Spryker\Zed\ProductBundle\Persistence\ProductBundleRepositoryInterface;

class ProductBundleReader implements ProductBundleReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface
     */
    protected $productBundleQueryContainer;

    /**
     * @var \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToAvailabilityFacadeInterface
     */
    protected $availabilityFacade;

    /**
     * @var \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\ProductBundle\Persistence\ProductBundleRepositoryInterface
     */
    protected $productBundleRepository;

    /**
     * @var \Spryker\Zed\ProductBundle\Business\ProductBundle\Cache\ProductBundleCacheInterface
     */
    protected $productBundleCache;

    /**
     * @param \Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface $productBundleQueryContainer
     * @param \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToAvailabilityFacadeInterface $availabilityFacade
     * @param \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\ProductBundle\Persistence\ProductBundleRepositoryInterface $productBundleRepository
     * @param \Spryker\Zed\ProductBundle\Business\ProductBundle\Cache\ProductBundleCacheInterface $productBundleCache
     */
    public function __construct(
        ProductBundleQueryContainerInterface $productBundleQueryContainer,
        ProductBundleToAvailabilityFacadeInterface $availabilityFacade,
        ProductBundleToStoreFacadeInterface $storeFacade,
        ProductBundleRepositoryInterface $productBundleRepository,
        ProductBundleCacheInterface $productBundleCache
    ) {
        $this->productBundleQueryContainer = $productBundleQueryContainer;
        $this->availabilityFacade = $availabilityFacade;
        $this->storeFacade = $storeFacade;
        $this->productBundleRepository = $productBundleRepository;
        $this->productBundleCache = $productBundleCache;
    }

    /**
     * @param int $idProductConcrete
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ProductForBundleTransfer>
     */
    public function findBundledProductsByIdProductConcrete($idProductConcrete)
    {
        $bundledProducts = $this->findBundledProducts($idProductConcrete);

        $bundledProductsTransferCollection = new ArrayObject();
        foreach ($bundledProducts as $bundledProductEntity) {
            $productForBundleTransfer = new ProductForBundleTransfer();
            $productForBundleTransfer->setIdProductConcrete($bundledProductEntity->getFkBundledProduct());

            $sku = $bundledProductEntity->getSpyProductRelatedByFkBundledProduct()->getSku();
            $productForBundleTransfer->setSku($sku);

            $productForBundleTransfer->fromArray($bundledProductEntity->toArray(), true);
            $bundledProductsTransferCollection->append($productForBundleTransfer);
        }

        return $bundledProductsTransferCollection;
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\ProductBundle\Business\ProductBundle\ProductBundleReader::expandProductConcreteTransfersWithBundledProducts()} instead.
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function assignBundledProductsToProductConcrete(ProductConcreteTransfer $productConcreteTransfer)
    {
        $productConcreteTransfersWithBundledProducts = $this->expandProductConcreteTransfersWithBundledProducts([$productConcreteTransfer]);

        return array_shift($productConcreteTransfersWithBundledProducts);
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductConcreteTransfer> $productConcreteTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function expandProductConcreteTransfersWithBundledProducts(array $productConcreteTransfers): array
    {
        $productIds = [];
        $productSkus = [];

        foreach ($productConcreteTransfers as $productConcreteTransfer) {
            $productIds[] = $productConcreteTransfer->getIdProductConcreteOrFail();
            $productSkus[] = $productConcreteTransfer->getSkuOrFail();
        }

        $productBundleTransfers = $this->productBundleRepository->getProductBundleTransfersIndexedByIdProductConcrete($productIds);
        $productConcreteAvailabilityIndexedBySkuForStore = $this->getProductConcreteAvailabilityIndexedBySkuForStore($productSkus);

        foreach ($productConcreteTransfers as $productConcreteTransfer) {
            $productBundleTransfer = $productBundleTransfers[$productConcreteTransfer->getIdProductConcrete()] ?? null;
            if ($productBundleTransfer === null) {
                continue;
            }

            $productBundleAvailabilityTransfer = $productConcreteAvailabilityIndexedBySkuForStore[$productConcreteTransfer->getSkuOrFail()] ?? null;

            if ($productBundleAvailabilityTransfer !== null) {
                $productBundleTransfer->setAvailability($productBundleAvailabilityTransfer->getAvailability());
                $productBundleTransfer->setIsNeverOutOfStock($productBundleAvailabilityTransfer->getIsNeverOutOfStock());
            }

            $productConcreteTransfer->setProductBundle($productBundleTransfer);
        }

        return $productConcreteTransfers;
    }

    /**
     * @param int $idProductConcrete
     *
     * @return \Propel\Runtime\Collection\Collection<\Orm\Zed\ProductBundle\Persistence\SpyProductBundle>
     */
    protected function findBundledProducts($idProductConcrete)
    {
        return $this->productBundleQueryContainer
            ->queryBundleWithRelatedBundledProduct($idProductConcrete)
            ->find();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer|null
     */
    protected function findProductConcreteAvailabilityBySkuForStore(ProductConcreteTransfer $productConcreteTransfer): ?ProductConcreteAvailabilityTransfer
    {
        $storeTransfer = $this->storeFacade->getCurrentStore();

        return $this->availabilityFacade
            ->findOrCreateProductConcreteAvailabilityBySkuForStore($productConcreteTransfer->getSku(), $storeTransfer);
    }

    /**
     * Result format:
     * [
     *     $productConcreteSku => ProductConcreteAvailabilityTransfer,
     *     ...,
     * ]
     *
     * @param array<string> $productConcreteSkus
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer>
     */
    protected function getProductConcreteAvailabilityIndexedBySkuForStore(array $productConcreteSkus): array
    {
        if (!$this->storeFacade->isCurrentStoreDefined()) {
            return [];
        }

        $storeTransfer = $this->storeFacade->getCurrentStore();

        $productAvailabilityCriteriaTransfer = (new ProductAvailabilityCriteriaTransfer())
            ->addIdStore($storeTransfer->getIdStoreOrFail())
            ->setProductConcreteSkus($productConcreteSkus);

        $productConcreteAvailabilityCollectionTransfer = $this->availabilityFacade
            ->getProductConcreteAvailabilityCollection($productAvailabilityCriteriaTransfer);

        $result = [];
        $foundSkus = [];

        foreach ($productConcreteAvailabilityCollectionTransfer->getProductConcreteAvailabilities() as $productConcreteAvailabilityTransfer) {
            $result[$productConcreteAvailabilityTransfer->getSkuOrFail()] = $productConcreteAvailabilityTransfer;
            $foundSkus[] = $productConcreteAvailabilityTransfer->getSkuOrFail();
        }

        $missedSkus = array_diff($productConcreteSkus, $foundSkus);

        foreach ($missedSkus as $sku) {
            $result[$sku] = $this->availabilityFacade->findOrCreateProductConcreteAvailabilityBySkuForStore($sku, $storeTransfer);
        }

        return $result;
    }

    /**
     * @param array<string> $skus
     *
     * @return array<array<\Generated\Shared\Transfer\ProductForBundleTransfer>>
     */
    public function getProductForBundleTransfersByProductConcreteSkus(array $skus): array
    {
        $notCachedSkus = [];
        foreach ($skus as $sku) {
            if (!$this->productBundleCache->hasProductForBundleTransfersBySku($sku)) {
                $notCachedSkus[] = $sku;
            }
        }

        if ($notCachedSkus) {
            $productForBundleTransfers = $this->productBundleRepository->getProductForBundleTransfersByProductConcreteSkus($notCachedSkus);
            $this->productBundleCache->cacheProductForBundleTransfersBySkus($productForBundleTransfers, $notCachedSkus);
        }

        $productForBundlesBySku = [];
        foreach ($skus as $sku) {
            if ($this->productBundleCache->hasProductForBundleTransfersBySku($sku)) {
                $productForBundlesBySku[$sku] = $this->productBundleCache->getProductForBundleTransfersBySku($sku);
            }
        }

        return $productForBundlesBySku;
    }
}
