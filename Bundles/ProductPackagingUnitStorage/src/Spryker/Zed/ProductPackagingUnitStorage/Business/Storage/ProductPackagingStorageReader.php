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
    public function getProductAbstractPackagingTransfers(array $productAbstractIds): array
    {
        $productAbstractPackagingTransfers = [];

        foreach ($productAbstractIds as $productAbstractId) {
            $productPackagingLeadProduct = $this->getProductPackagingLeadProductByAbstractId($productAbstractId);
            $packageProductConcreteEntities = $this->getPackageProductsByAbstractId($productAbstractId);

            $productAbstractPackagingTransfers[] = $this->mapProductAbstractPackagingTransfer(
                $productAbstractId,
                $productPackagingLeadProduct,
                $packageProductConcreteEntities
            );
        }

        return $productAbstractPackagingTransfers;
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
    protected function getPackageProductsByAbstractId(int $productAbstractId)
    {
        return $this->productPackagingUnitStorageRepository
            ->findPackagingProductsByAbstractId($productAbstractId);
    }

    /**
     * @param int $productAbstractId
     * @param \Generated\Shared\Transfer\ProductPackagingLeadProductTransfer $productPackagingLeadProductTransfer
     * @param array $packageProductConcreteEntities
     *
     * @return \Generated\Shared\Transfer\ProductAbstractPackagingStorageTransfer
     */
    protected function mapProductAbstractPackagingTransfer(
        int $productAbstractId,
        ProductPackagingLeadProductTransfer $productPackagingLeadProductTransfer,
        array $packageProductConcreteEntities
    ): ProductAbstractPackagingStorageTransfer {

        $idProduct = $productPackagingLeadProductTransfer ? $productPackagingLeadProductTransfer->getIdProduct() : null;
        $productAbstractPackagingTypes = $this->getProductAbstractPackagingTypes($packageProductConcreteEntities);
        $productAbstractPackagingStorageTransfer = $this->createProductAbstractPackagingStorageTransfer($productAbstractId, $idProduct, $productAbstractPackagingTypes);

        return $productAbstractPackagingStorageTransfer;
    }

    /**
     * @param array $packageProductConcreteEntities
     *
     * @return array
     */
    protected function getProductAbstractPackagingTypes(array $packageProductConcreteEntities): array
    {
        $productAbstractPackagingTypes = [];
        $defaultPackagingUnitTypeName = $this->getDefaultPackagingUnitTypeName();

        foreach ($packageProductConcreteEntities as $packageProductConcreteEntity) {
            $productAbstractPackagingType = (new ProductConcretePackagingStorageTransfer())
                ->setIdProduct($packageProductConcreteEntity->getIdProduct());

            $productPackagingUnitEntities = $packageProductConcreteEntity->getSpyProductPackagingUnits();

            if (!count($productPackagingUnitEntities)) {
                $productAbstractPackagingTypes[] = $productAbstractPackagingType;
                continue;
            }

            $productPackagingUnitEntity = $productPackagingUnitEntities[0];
            $productPackagingUnitTypeName = $productPackagingUnitEntity->getProductPackagingUnitType() && $productPackagingUnitEntity->getProductPackagingUnitType()->getName() ? $productPackagingUnitEntity->getProductPackagingUnitType()->getName() : $defaultPackagingUnitTypeName;

            $productAbstractPackagingType
                ->setHasLeadProduct($productPackagingUnitEntity->getHasLeadProduct())
                ->setName($productPackagingUnitTypeName);

            $productPackagingUnitAmounts = $productPackagingUnitEntity->getSpyProductPackagingUnitAmounts();

            if (!count($productPackagingUnitAmounts)) {
                $productAbstractPackagingTypes[] = $productAbstractPackagingType;
                continue;
            }

            $productPackagingUnitAmount = $productPackagingUnitAmounts[0];

            $productAbstractPackagingType
                ->setDefaultAmount($productPackagingUnitAmount->getDefaultAmount())
                ->setIsVariable($productPackagingUnitAmount->getIsVariable())
                ->setAmountMin($productPackagingUnitAmount->getAmountMin())
                ->setAmountMax($productPackagingUnitAmount->getAmountMax())
                ->setAmountInterval($productPackagingUnitAmount->getAmountInterval());

            $productAbstractPackagingTypes[] = $productAbstractPackagingType;
        }

        return $productAbstractPackagingTypes;
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
        $productAbstractPackagingStorageTransfer = new ProductAbstractPackagingStorageTransfer();

        $productAbstractPackagingStorageTransfer
            ->setIdProductAbstract($idProductAbstract)
            ->setLeadProduct($idProduct)
            ->setTypes(new ArrayObject($productAbstractPackagingTypes));

        return $productAbstractPackagingStorageTransfer;
    }
}
