<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitStorage\Business\Storage;

use ArrayObject;
use Generated\Shared\Transfer\ProductAbstractPackagingStorageTransfer;
use Generated\Shared\Transfer\ProductConcretePackagingStorageTransfer;
use Generated\Shared\Transfer\ProductPackagingLeadProductTransfer;
use Generated\Shared\Transfer\SpyProductEntityTransfer;
use Generated\Shared\Transfer\SpyProductPackagingUnitAmountEntityTransfer;
use Generated\Shared\Transfer\SpyProductPackagingUnitEntityTransfer;
use Spryker\Zed\ProductPackagingUnitStorage\Dependency\Facade\ProductPackagingUnitStorageToProductPackagingUnitFacadeInterface;
use Spryker\Zed\ProductPackagingUnitStorage\Persistence\ProductPackagingUnitStorageRepositoryInterface;

class ProductPackagingStorageReader implements ProductPackagingStorageReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductPackagingUnitStorage\Persistence\ProductPackagingUnitStorageRepositoryInterface
     */
    protected $productPackagingUnitStorageRepository;

    /**
     * @var \Spryker\Zed\ProductPackagingUnitStorage\Dependency\Facade\ProductPackagingUnitStorageToProductPackagingUnitFacadeInterface
     */
    protected $productPackagingUnitFacade;

    /**
     * @param \Spryker\Zed\ProductPackagingUnitStorage\Persistence\ProductPackagingUnitStorageRepositoryInterface $productPackagingUnitStorageRepository
     * @param \Spryker\Zed\ProductPackagingUnitStorage\Dependency\Facade\ProductPackagingUnitStorageToProductPackagingUnitFacadeInterface $productPackagingUnitFacade
     */
    public function __construct(
        ProductPackagingUnitStorageRepositoryInterface $productPackagingUnitStorageRepository,
        ProductPackagingUnitStorageToProductPackagingUnitFacadeInterface $productPackagingUnitFacade
    ) {
        $this->productPackagingUnitStorageRepository = $productPackagingUnitStorageRepository;
        $this->productPackagingUnitFacade = $productPackagingUnitFacade;
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return \Generated\Shared\Transfer\ProductAbstractPackagingStorageTransfer[]
     */
    public function getProductAbstractPackagingStorageTransfer(array $productAbstractIds): array
    {
        $productAbstractPackagingStoreTransfers = [];

        foreach ($productAbstractIds as $productAbstractId) {
            $productPackagingLeadProduct = $this->getProductPackagingLeadProductByAbstractId($productAbstractId);
            $packageProductConcreteEntityTransfers = $this->getPackageProductsByAbstractId($productAbstractId);

            $productAbstractPackagingStoreTransfers[] = $this->hydrateProductAbstractPackagingStoreTransfer(
                $productAbstractId,
                $productPackagingLeadProduct,
                $packageProductConcreteEntityTransfers
            );
        }

        return $productAbstractPackagingStoreTransfers;
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return \Generated\Shared\Transfer\SpyProductAbstractPackagingStorageEntityTransfer[]
     */
    public function getProductAbstractPackagingUnitStorageEntities(array $productAbstractIds): array
    {
        return $this->productPackagingUnitStorageRepository
            ->findProductAbstractPackagingUnitStorageByProductAbstractIds($productAbstractIds);
    }

    /**
     * @param int $productAbstractId
     *
     * @return \Generated\Shared\Transfer\ProductPackagingLeadProductTransfer|null
     */
    protected function getProductPackagingLeadProductByAbstractId(int $productAbstractId): ?ProductPackagingLeadProductTransfer
    {
        return $this->productPackagingUnitFacade
            ->getProductPackagingLeadProductByAbstractId($productAbstractId);
    }

    /**
     * @param int $productAbstractId
     *
     * @return \Generated\Shared\Transfer\SpyProductEntityTransfer[]
     */
    protected function getPackageProductsByAbstractId(int $productAbstractId): array
    {
        return $this->productPackagingUnitStorageRepository
            ->findPackagingProductsByAbstractId($productAbstractId);
    }

    /**
     * @param int $productAbstractId
     * @param \Generated\Shared\Transfer\ProductPackagingLeadProductTransfer $productPackagingLeadProductTransfer
     * @param \Generated\Shared\Transfer\SpyProductEntityTransfer[] $packageProductConcreteEntityTransfers
     *
     * @return \Generated\Shared\Transfer\ProductAbstractPackagingStorageTransfer
     */
    protected function hydrateProductAbstractPackagingStoreTransfer(
        int $productAbstractId,
        ProductPackagingLeadProductTransfer $productPackagingLeadProductTransfer,
        array $packageProductConcreteEntityTransfers
    ): ProductAbstractPackagingStorageTransfer {

        $idProduct = $productPackagingLeadProductTransfer ? $productPackagingLeadProductTransfer->getIdProduct() : null;
        $productAbstractPackagingTypes = $this->getProductAbstractPackagingTypes($packageProductConcreteEntityTransfers);
        $productAbstractPackagingStorageTransfer = $this->createProductAbstractPackagingStorageTransfer($productAbstractId, $idProduct, $productAbstractPackagingTypes);

        return $productAbstractPackagingStorageTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyProductEntityTransfer[] $packageProductConcreteEntityTransfers
     *
     * @return array
     */
    protected function getProductAbstractPackagingTypes(array $packageProductConcreteEntityTransfers): array
    {
        $productConcretePackagingStorageTransfers = [];
        $defaultPackagingUnitTypeName = $this->getDefaultPackagingUnitTypeName();

        foreach ($packageProductConcreteEntityTransfers as $packageProductConcreteEntityTransfer) {
            $productConcretePackagingStorageTransfer = $this->createProductConcretePackagingStorageTransfer($packageProductConcreteEntityTransfer);

            if (!$packageProductConcreteEntityTransfer->getSpyProductPackagingUnits()->count()) {
                $productConcretePackagingStorageTransfers[] = $productConcretePackagingStorageTransfer;
                continue;
            }

            list($productPackagingUnitEntityTransfer) = $packageProductConcreteEntityTransfer->getSpyProductPackagingUnits();
            $productPackagingUnitTypeName = $this->getProductPackagingUnitTypeName($productPackagingUnitEntityTransfer, $defaultPackagingUnitTypeName);

            $productConcretePackagingStorageTransfer
                ->setHasLeadProduct($productPackagingUnitEntityTransfer->getHasLeadProduct())
                ->setName($productPackagingUnitTypeName);

            if (!$productPackagingUnitEntityTransfer->getSpyProductPackagingUnitAmounts()->count()) {
                $productConcretePackagingStorageTransfers[] = $productConcretePackagingStorageTransfer;
                continue;
            }

            list($productPackagingUnitAmountEntityTransfer) = $productPackagingUnitEntityTransfer->getSpyProductPackagingUnitAmounts();
            $productConcretePackagingStorageTransfer = $this->getProductAbstractPackagingType($productConcretePackagingStorageTransfer, $productPackagingUnitAmountEntityTransfer);
            $productConcretePackagingStorageTransfers[] = $productConcretePackagingStorageTransfer;
        }

        return $productConcretePackagingStorageTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyProductPackagingUnitEntityTransfer $productPackagingUnitEntityTransfer
     * @param string $defaultPackagingUnitTypeName
     *
     * @return string
     */
    protected function getProductPackagingUnitTypeName(SpyProductPackagingUnitEntityTransfer $productPackagingUnitEntityTransfer, string $defaultPackagingUnitTypeName): string
    {
        return $productPackagingUnitEntityTransfer->getProductPackagingUnitType() ?
               $productPackagingUnitEntityTransfer->getProductPackagingUnitType()->getName() :
               $defaultPackagingUnitTypeName;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyProductEntityTransfer $productEntityTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcretePackagingStorageTransfer
     */
    protected function createProductConcretePackagingStorageTransfer(SpyProductEntityTransfer $productEntityTransfer): ProductConcretePackagingStorageTransfer
    {
        return (new ProductConcretePackagingStorageTransfer())
            ->setIdProduct($productEntityTransfer->getIdProduct());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcretePackagingStorageTransfer $productConcretePackagingStorageTransfer
     * @param \Generated\Shared\Transfer\SpyProductPackagingUnitAmountEntityTransfer $productPackagingUnitAmountEntityTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcretePackagingStorageTransfer
     */
    protected function getProductAbstractPackagingType(
        ProductConcretePackagingStorageTransfer $productConcretePackagingStorageTransfer,
        SpyProductPackagingUnitAmountEntityTransfer $productPackagingUnitAmountEntityTransfer
    ): ProductConcretePackagingStorageTransfer {
        $productConcretePackagingStorageTransfer
            ->setDefaultAmount($productPackagingUnitAmountEntityTransfer->getDefaultAmount())
            ->setIsVariable($productPackagingUnitAmountEntityTransfer->getIsVariable())
            ->setAmountMin($productPackagingUnitAmountEntityTransfer->getAmountMin())
            ->setAmountMax($productPackagingUnitAmountEntityTransfer->getAmountMax())
            ->setAmountInterval($productPackagingUnitAmountEntityTransfer->getAmountInterval());

        return $productConcretePackagingStorageTransfer;
    }

    /**
     * @return string
     */
    protected function getDefaultPackagingUnitTypeName(): string
    {
        return $this->productPackagingUnitFacade->getDefaultPackagingUnitTypeName();
    }

    /**
     * @param int $idProductAbstract
     * @param int $idProduct
     * @param array $productAbstractPackagingTypes
     *
     * @return \Generated\Shared\Transfer\ProductAbstractPackagingStorageTransfer
     */
    protected function createProductAbstractPackagingStorageTransfer(int $idProductAbstract, int $idProduct, array $productAbstractPackagingTypes): ProductAbstractPackagingStorageTransfer
    {
        return (new ProductAbstractPackagingStorageTransfer())
            ->setIdProductAbstract($idProductAbstract)
            ->setLeadProduct($idProduct)
            ->setTypes(new ArrayObject($productAbstractPackagingTypes));
    }
}
