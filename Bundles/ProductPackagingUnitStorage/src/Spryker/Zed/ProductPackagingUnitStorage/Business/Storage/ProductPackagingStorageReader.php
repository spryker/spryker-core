<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitStorage\Business\Storage;

use ArrayObject;
use Generated\Shared\Transfer\ProductAbstractPackagingStorageTransfer;
use Generated\Shared\Transfer\ProductConcretePackagingStorageAmountTransfer;
use Generated\Shared\Transfer\ProductConcretePackagingStorageTransfer;
use Generated\Shared\Transfer\ProductPackagingLeadProductTransfer;
use Generated\Shared\Transfer\SpyProductEntityTransfer;
use Generated\Shared\Transfer\SpyProductPackagingUnitAmountEntityTransfer;
use Generated\Shared\Transfer\SpyProductPackagingUnitEntityTransfer;
use Spryker\Zed\ProductPackagingUnitStorage\Dependency\Facade\ProductPackagingUnitStorageToProductPackagingUnitFacadeInterface;
use Spryker\Zed\ProductPackagingUnitStorage\Persistence\ProductPackagingUnitStorageRepositoryInterface;
use Spryker\Zed\ProductPackagingUnitStorage\ProductPackagingUnitStorageConfig;

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
     * @var \Spryker\Zed\ProductPackagingUnitStorage\ProductPackagingUnitStorageConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\ProductPackagingUnitStorage\Persistence\ProductPackagingUnitStorageRepositoryInterface $productPackagingUnitStorageRepository
     * @param \Spryker\Zed\ProductPackagingUnitStorage\Dependency\Facade\ProductPackagingUnitStorageToProductPackagingUnitFacadeInterface $productPackagingUnitFacade
     * @param \Spryker\Zed\ProductPackagingUnitStorage\ProductPackagingUnitStorageConfig $config
     */
    public function __construct(
        ProductPackagingUnitStorageRepositoryInterface $productPackagingUnitStorageRepository,
        ProductPackagingUnitStorageToProductPackagingUnitFacadeInterface $productPackagingUnitFacade,
        ProductPackagingUnitStorageConfig $config
    ) {
        $this->productPackagingUnitStorageRepository = $productPackagingUnitStorageRepository;
        $this->productPackagingUnitFacade = $productPackagingUnitFacade;
        $this->config = $config;
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
            $productConcretePackagingStorageTransfer->setName($this->config->getProductAbstractStorageDefaultName());
        }
        $productConcretePackagingStorageTransfer->fromArray($this->createDefaultProductConcretePackagingStorageAmountTransfer());
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
     * @return array
     */
    protected function createDefaultProductConcretePackagingStorageAmountTransfer(): array
    {
        $productConcretePackagingStorageAmountTransfer = new ProductConcretePackagingStorageAmountTransfer();
        $productConcretePackagingStorageAmountTransfer
            ->setDefaultAmount($this->config->getProductAbstractStorageDefaultAmountValue())
            ->setIsVariable($this->config->isProductAbstractStorageDefaultIsVariableValue());

        return $productConcretePackagingStorageAmountTransfer->toArray();
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
            $amountMin = $productPackagingUnitAmountEntityTransfer->getAmountMin() ?: $this->config->getProductAbstractStorageDefaultAmountMin();
            $amountInterval = $productPackagingUnitAmountEntityTransfer->getAmountInterval() ?: $this->config->getProductAbstractStorageDefaultAmountInterval();

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
