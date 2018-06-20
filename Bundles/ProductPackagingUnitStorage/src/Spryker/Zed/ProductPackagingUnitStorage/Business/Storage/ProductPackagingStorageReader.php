<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitStorage\Business\Storage;

use ArrayObject;
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
     * @uses \Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnit\ProductPackagingUnitReader::PRODUCT_ABSTRACT_STORAGE_DEFAULT_VALUES.
     *
     * default values for packaging unit storage values.
     */
    protected const PRODUCT_ABSTRACT_STORAGE_DEFAULT_VALUES = [
        ProductPackagingUnitAmountTransfer::DEFAULT_AMOUNT => 1,
        ProductPackagingUnitAmountTransfer::IS_VARIABLE => false,
        ProductPackagingUnitAmountTransfer::AMOUNT_MIN => 1,
        ProductPackagingUnitAmountTransfer::AMOUNT_INTERVAL => 1,
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
     * @param int[] $idProductAbstracts
     *
     * @return \Generated\Shared\Transfer\ProductAbstractPackagingStorageTransfer[]
     */
    public function getProductAbstractPackagingStorageTransfer(array $idProductAbstracts): array
    {
        $productAbstractPackagingStoreTransfers = [];

        foreach ($idProductAbstracts as $idProductAbstract) {
            $packageProductConcreteEntityTransfers = $this->getPackageProductsByAbstractId($idProductAbstract);

            if ($packageProductConcreteEntityTransfers) {
                list($productPackagingLeadProduct) = $packageProductConcreteEntityTransfers[0]->getSpyProductPackagingLeadProducts();
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
     * @param int[] $idProductAbstracts
     *
     * @return \Generated\Shared\Transfer\SpyProductAbstractPackagingStorageEntityTransfer[]
     */
    public function getProductAbstractPackagingUnitStorageEntities(array $idProductAbstracts): array
    {
        return $this->productPackagingUnitStorageRepository
            ->findProductAbstractPackagingUnitStorageByProductAbstractIds($idProductAbstracts);
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\SpyProductEntityTransfer[]
     */
    protected function getPackageProductsByAbstractId(int $idProductAbstract): array
    {
        return $this->productPackagingUnitStorageRepository
            ->findPackagingProductsByAbstractId($idProductAbstract);
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
        $defaultPackagingUnitTypeName = $this->getDefaultPackagingUnitTypeName();

        foreach ($packageProductConcreteEntityTransfers as $packageProductConcreteEntityTransfer) {
            $productConcretePackagingStorageTransfer = $this->createProductConcretePackagingStorageTransfer($packageProductConcreteEntityTransfer);
            $hasProductPackagingUnit = $packageProductConcreteEntityTransfer->getSpyProductPackagingUnits()->count();

            if (!$hasProductPackagingUnit) {
                $this->getDefaultParameters($productConcretePackagingStorageTransfer, $hasProductPackagingUnit);
                $productConcretePackagingStorageTransfers[] = $productConcretePackagingStorageTransfer;
                continue;
            }

            list($productPackagingUnitEntityTransfer) = $packageProductConcreteEntityTransfer->getSpyProductPackagingUnits();
            $this->getProductAbstractPackagingStorageNameAndLead($productConcretePackagingStorageTransfer, $productPackagingUnitEntityTransfer, $defaultPackagingUnitTypeName);

            if (!$productPackagingUnitEntityTransfer->getSpyProductPackagingUnitAmounts()->count()) {
                $this->getDefaultParameters($productConcretePackagingStorageTransfer, false);
                $productConcretePackagingStorageTransfers[] = $productConcretePackagingStorageTransfer;
                continue;
            }

            list($productPackagingUnitAmountEntityTransfer) = $productPackagingUnitEntityTransfer->getSpyProductPackagingUnitAmounts();
            $this->getProductAbstractPackagingStorageAmount($productConcretePackagingStorageTransfer, $productPackagingUnitAmountEntityTransfer);
            $productConcretePackagingStorageTransfers[] = $productConcretePackagingStorageTransfer;
        }

        return $productConcretePackagingStorageTransfers;
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
            $productConcretePackagingStorageTransfer->setName($this->getDefaultPackagingUnitTypeName());
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

        $productConcretePackagingStorageTransfer
            ->setHasLeadProduct($productPackagingUnitEntityTransfer->getHasLeadProduct())
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
            $amountMax = $productPackagingUnitAmountEntityTransfer->getAmountMax();
            $amountMin = $productPackagingUnitAmountEntityTransfer->getAmountMin() ?: $this->createDefaultProductPackagingUnitAmountTransfer()->getAmountMin();
            $amountInterval = $productPackagingUnitAmountEntityTransfer->getAmountInterval() ?: $this->createDefaultProductPackagingUnitAmountTransfer()->getAmountInterval();

            $productConcretePackagingStorageTransfer
                ->setAmountMin($amountMax)
                ->setAmountMax($amountMin)
                ->setAmountInterval($amountInterval);
        }
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
