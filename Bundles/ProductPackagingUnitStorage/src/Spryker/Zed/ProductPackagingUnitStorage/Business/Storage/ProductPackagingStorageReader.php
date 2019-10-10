<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitStorage\Business\Storage;

use ArrayObject;
use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\ProductAbstractPackagingStorageTransfer;
use Generated\Shared\Transfer\ProductConcretePackagingStorageTransfer;
use Generated\Shared\Transfer\ProductPackagingUnitAmountTransfer;
use Generated\Shared\Transfer\SpyProductEntityTransfer;
use Generated\Shared\Transfer\SpyProductPackagingLeadProductEntityTransfer;
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
     * Default values for packaging unit storage values.
     *
     * @see \Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnit\ProductPackagingUnitReader::PRODUCT_ABSTRACT_STORAGE_DEFAULT_VALUES
     */
    protected const PRODUCT_ABSTRACT_STORAGE_DEFAULT_VALUES = [
        ProductPackagingUnitAmountTransfer::DEFAULT_AMOUNT => 1,
        ProductPackagingUnitAmountTransfer::IS_VARIABLE => false,
    ];

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

        foreach ($productAbstractIds as $idProductAbstract) {
            $packageProductConcreteEntityTransfers = $this->getPackageProductsByAbstractId($idProductAbstract);

            if (!empty($packageProductConcreteEntityTransfers)) {
                [$packageProductConcreteEntityTransfer] = $packageProductConcreteEntityTransfers;
                [$productPackagingLeadProduct] = $packageProductConcreteEntityTransfer->getSpyProductAbstract()->getSpyProductPackagingLeadProducts();
                $productAbstractPackagingStoreTransfers[] = $this->hydrateProductAbstractPackagingStoreTransfer(
                    $idProductAbstract,
                    $productPackagingLeadProduct,
                    $packageProductConcreteEntityTransfers
                );
            }
        }

        return $productAbstractPackagingStoreTransfers;
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return \Generated\Shared\Transfer\SpyProductAbstractPackagingStorageEntityTransfer[]
     */
    public function getProductAbstractPackagingStorageEntities(array $productAbstractIds): array
    {
        return $this->productPackagingUnitStorageRepository
            ->findProductAbstractPackagingStorageEntitiesByProductAbstractIds($productAbstractIds);
    }

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return \Generated\Shared\Transfer\SpyProductPackagingLeadProductEntityTransfer[]
     */
    public function getProductPackagingLeadProductEntityByFilter(FilterTransfer $filterTransfer): array
    {
        return $this->productPackagingUnitStorageRepository
            ->getProductPackagingLeadProductEntityByFilter($filterTransfer);
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\SpyProductEntityTransfer[]
     */
    protected function getPackageProductsByAbstractId(int $idProductAbstract): array
    {
        return $this->productPackagingUnitStorageRepository
            ->findPackagingProductsByProductAbstractId($idProductAbstract);
    }

    /**
     * @param int $idProductAbstract
     * @param \Generated\Shared\Transfer\SpyProductPackagingLeadProductEntityTransfer $productPackagingLeadProductEntityTransfer
     * @param \Generated\Shared\Transfer\SpyProductEntityTransfer[] $packageProductConcreteEntityTransfers
     *
     * @return \Generated\Shared\Transfer\ProductAbstractPackagingStorageTransfer
     */
    protected function hydrateProductAbstractPackagingStoreTransfer(
        int $idProductAbstract,
        SpyProductPackagingLeadProductEntityTransfer $productPackagingLeadProductEntityTransfer,
        array $packageProductConcreteEntityTransfers
    ): ProductAbstractPackagingStorageTransfer {
        $idProduct = $productPackagingLeadProductEntityTransfer->getFkProduct();
        $productAbstractPackagingTypes = $this->getProductAbstractPackagingTypes($packageProductConcreteEntityTransfers);

        $productAbstractPackagingStorageTransfer = $this->createProductAbstractPackagingStorageTransfer($idProductAbstract, $idProduct, $productAbstractPackagingTypes);

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
        $defaultPackagingUnitTypeName = $this->getDefaultProductPackagingUnitTypeName();

        foreach ($packageProductConcreteEntityTransfers as $packageProductConcreteEntityTransfer) {
            $productConcretePackagingStorageTransfer = $this->createProductConcretePackagingStorageTransfer($packageProductConcreteEntityTransfer);
            $hasProductPackagingUnit = $this->hasProductPackagingUnit($packageProductConcreteEntityTransfer);

            if (!$hasProductPackagingUnit) {
                $this->getDefaultParameters($productConcretePackagingStorageTransfer, $hasProductPackagingUnit);
                $productConcretePackagingStorageTransfers[] = $productConcretePackagingStorageTransfer;
                continue;
            }

            [$productPackagingUnitEntityTransfer] = $packageProductConcreteEntityTransfer->getSpyProductPackagingUnits();
            $this->getProductAbstractPackagingStorageNameAndLead($productConcretePackagingStorageTransfer, $productPackagingUnitEntityTransfer, $defaultPackagingUnitTypeName);

            if (!$productPackagingUnitEntityTransfer->getSpyProductPackagingUnitAmounts()->count()) {
                $this->getDefaultParameters($productConcretePackagingStorageTransfer, false);
                $productConcretePackagingStorageTransfers[] = $productConcretePackagingStorageTransfer;
                continue;
            }

            [$productPackagingUnitAmountEntityTransfer] = $productPackagingUnitEntityTransfer->getSpyProductPackagingUnitAmounts();
            $this->getProductAbstractPackagingStorageAmount($productConcretePackagingStorageTransfer, $productPackagingUnitAmountEntityTransfer);
            $productConcretePackagingStorageTransfers[] = $productConcretePackagingStorageTransfer;
        }

        return $productConcretePackagingStorageTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyProductEntityTransfer $productEntityTransfer
     *
     * @return bool
     */
    protected function hasProductPackagingUnit(SpyProductEntityTransfer $productEntityTransfer): bool
    {
        return $productEntityTransfer->getSpyProductPackagingUnits()->count() > 0;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcretePackagingStorageTransfer $productConcretePackagingStorageTransfer
     * @param bool $hasProductPackagingUnit
     *
     * @return void
     */
    protected function getDefaultParameters(ProductConcretePackagingStorageTransfer $productConcretePackagingStorageTransfer, bool $hasProductPackagingUnit): void
    {
        if ($hasProductPackagingUnit) {
            $productConcretePackagingStorageTransfer->setName($this->getDefaultProductPackagingUnitTypeName());
        }

        $productConcretePackagingStorageTransfer->fromArray($this->createDefaultProductPackagingUnitAmountTransfer()->toArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcretePackagingStorageTransfer $productConcretePackagingStorageTransfer
     * @param \Generated\Shared\Transfer\SpyProductPackagingUnitEntityTransfer $productPackagingUnitEntityTransfer
     * @param string $defaultPackagingUnitTypeName
     *
     * @return void
     */
    protected function getProductAbstractPackagingStorageNameAndLead(
        ProductConcretePackagingStorageTransfer $productConcretePackagingStorageTransfer,
        SpyProductPackagingUnitEntityTransfer $productPackagingUnitEntityTransfer,
        string $defaultPackagingUnitTypeName
    ): void {
        $productPackagingUnitTypeName = $this->getProductPackagingUnitTypeName($productPackagingUnitEntityTransfer, $defaultPackagingUnitTypeName);

        $hasLeadProduct = ($productPackagingUnitEntityTransfer->getHasLeadProduct() !== null) ? $productPackagingUnitEntityTransfer->getHasLeadProduct() : false;
        $productConcretePackagingStorageTransfer
            ->setHasLeadProduct($hasLeadProduct)
            ->setName($productPackagingUnitTypeName);
    }

    /**
     * @return \Generated\Shared\Transfer\ProductPackagingUnitAmountTransfer
     */
    protected function createDefaultProductPackagingUnitAmountTransfer(): ProductPackagingUnitAmountTransfer
    {
        return (new ProductPackagingUnitAmountTransfer())
            ->fromArray(
                static::PRODUCT_ABSTRACT_STORAGE_DEFAULT_VALUES
            );
    }

    /**
     * @param \Generated\Shared\Transfer\SpyProductPackagingUnitEntityTransfer $productPackagingUnitEntityTransfer
     * @param string $defaultPackagingUnitTypeName
     *
     * @return string
     */
    protected function getProductPackagingUnitTypeName(SpyProductPackagingUnitEntityTransfer $productPackagingUnitEntityTransfer, string $defaultPackagingUnitTypeName): string
    {
        if ($productPackagingUnitEntityTransfer->getProductPackagingUnitType() === null || $productPackagingUnitEntityTransfer->getProductPackagingUnitType()->getName() === null) {
            return $defaultPackagingUnitTypeName;
        }

        return $productPackagingUnitEntityTransfer->getProductPackagingUnitType()->getName();
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
     * @return void
     */
    protected function getProductAbstractPackagingStorageAmount(
        ProductConcretePackagingStorageTransfer $productConcretePackagingStorageTransfer,
        SpyProductPackagingUnitAmountEntityTransfer $productPackagingUnitAmountEntityTransfer
    ): void {
        $productConcretePackagingStorageTransfer
            ->setDefaultAmount($productPackagingUnitAmountEntityTransfer->getDefaultAmount())
            ->setIsVariable($productPackagingUnitAmountEntityTransfer->getIsVariable());

        if ($productPackagingUnitAmountEntityTransfer->getIsVariable()) {
            $amountInterval = $productPackagingUnitAmountEntityTransfer->getAmountInterval() ?? $this->createDefaultProductPackagingUnitAmountTransfer()->getAmountInterval();
            $amountMin = $productPackagingUnitAmountEntityTransfer->getAmountMin() ?? $amountInterval;
            $amountMax = $productPackagingUnitAmountEntityTransfer->getAmountMax();

            $productConcretePackagingStorageTransfer
                ->setAmountMin($amountMin)
                ->setAmountMax($amountMax)
                ->setAmountInterval($amountInterval);
        }
    }

    /**
     * @return string
     */
    protected function getDefaultProductPackagingUnitTypeName(): string
    {
        return $this->productPackagingUnitFacade->getDefaultProductPackagingUnitTypeName();
    }

    /**
     * @param int $idProductAbstract
     * @param int|null $idProduct
     * @param array $productAbstractPackagingTypes
     *
     * @return \Generated\Shared\Transfer\ProductAbstractPackagingStorageTransfer
     */
    protected function createProductAbstractPackagingStorageTransfer(int $idProductAbstract, ?int $idProduct, array $productAbstractPackagingTypes): ProductAbstractPackagingStorageTransfer
    {
        return (new ProductAbstractPackagingStorageTransfer())
            ->setIdProductAbstract($idProductAbstract)
            ->setLeadProduct($idProduct)
            ->setTypes(new ArrayObject($productAbstractPackagingTypes));
    }
}
