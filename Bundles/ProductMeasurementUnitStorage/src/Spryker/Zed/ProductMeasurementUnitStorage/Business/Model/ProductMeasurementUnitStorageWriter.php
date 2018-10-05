<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnitStorage\Business\Model;

use Generated\Shared\Transfer\ProductMeasurementUnitStorageTransfer;
use Generated\Shared\Transfer\ProductMeasurementUnitTransfer;
use Generated\Shared\Transfer\SpyProductMeasurementUnitStorageEntityTransfer;
use Spryker\Zed\ProductMeasurementUnitStorage\Dependency\Facade\ProductMeasurementUnitStorageToProductMeasurementUnitFacadeInterface;
use Spryker\Zed\ProductMeasurementUnitStorage\Persistence\ProductMeasurementUnitStorageEntityManagerInterface;
use Spryker\Zed\ProductMeasurementUnitStorage\Persistence\ProductMeasurementUnitStorageRepositoryInterface;

class ProductMeasurementUnitStorageWriter implements ProductMeasurementUnitStorageWriterInterface
{
    /**
     * @var \Spryker\Zed\ProductMeasurementUnitStorage\Dependency\Facade\ProductMeasurementUnitStorageToProductMeasurementUnitFacadeInterface
     */
    protected $productMeasurementUnitFacade;

    /**
     * @var \Spryker\Zed\ProductMeasurementUnitStorage\Persistence\ProductMeasurementUnitStorageRepositoryInterface
     */
    protected $productMeasurementUnitStorageRepository;

    /**
     * @var \Spryker\Zed\ProductMeasurementUnitStorage\Persistence\ProductMeasurementUnitStorageEntityManagerInterface
     */
    protected $productMeasurementUnitStorageEntityManager;

    /**
     * @param \Spryker\Zed\ProductMeasurementUnitStorage\Dependency\Facade\ProductMeasurementUnitStorageToProductMeasurementUnitFacadeInterface $productMeasurementUnitFacade
     * @param \Spryker\Zed\ProductMeasurementUnitStorage\Persistence\ProductMeasurementUnitStorageRepositoryInterface $productMeasurementUnitStorageRepository
     * @param \Spryker\Zed\ProductMeasurementUnitStorage\Persistence\ProductMeasurementUnitStorageEntityManagerInterface $productMeasurementUnitStorageEntityManager
     */
    public function __construct(
        ProductMeasurementUnitStorageToProductMeasurementUnitFacadeInterface $productMeasurementUnitFacade,
        ProductMeasurementUnitStorageRepositoryInterface $productMeasurementUnitStorageRepository,
        ProductMeasurementUnitStorageEntityManagerInterface $productMeasurementUnitStorageEntityManager
    ) {
        $this->productMeasurementUnitFacade = $productMeasurementUnitFacade;
        $this->productMeasurementUnitStorageRepository = $productMeasurementUnitStorageRepository;
        $this->productMeasurementUnitStorageEntityManager = $productMeasurementUnitStorageEntityManager;
    }

    /**
     * @param int[] $productMeasurementUnitIds
     *
     * @return void
     */
    public function publish(array $productMeasurementUnitIds): void
    {
        $productMeasurementUnitTransfers = $this->findProductMeasurementUnitTransfers($productMeasurementUnitIds);
        $productMeasurementUnitStorageEntities = $this->findProductMeasurementUnitStorageEntities($productMeasurementUnitIds);
        $mappedProductMeasurementUnitStorageEntities = $this->mapProductMeasurementUnitStorageEntities($productMeasurementUnitStorageEntities);

        foreach ($productMeasurementUnitTransfers as $productMeasurementUnitTransfer) {
            $idProductMeasurementUnit = $productMeasurementUnitTransfer->getIdProductMeasurementUnit();
            $productMeasurementUnitStorageEntity = $this->selectStorageEntity($mappedProductMeasurementUnitStorageEntities, $idProductMeasurementUnit);

            unset($mappedProductMeasurementUnitStorageEntities[$idProductMeasurementUnit]);

            $this->saveStorageEntity($productMeasurementUnitStorageEntity, $productMeasurementUnitTransfer);
        }

        $this->deleteStorageEntities($mappedProductMeasurementUnitStorageEntities);
    }

    /**
     * @param \Generated\Shared\Transfer\SpyProductMeasurementUnitStorageEntityTransfer $productMeasurementUnitStorageEntity
     * @param \Generated\Shared\Transfer\ProductMeasurementUnitTransfer $productMeasurementUnitTransfer
     *
     * @return void
     */
    protected function saveStorageEntity(
        SpyProductMeasurementUnitStorageEntityTransfer $productMeasurementUnitStorageEntity,
        ProductMeasurementUnitTransfer $productMeasurementUnitTransfer
    ): void {
        $productMeasurementUnitStorageEntity
            ->setFkProductMeasurementUnit($productMeasurementUnitTransfer->getIdProductMeasurementUnit())
            ->setData(
                (new ProductMeasurementUnitStorageTransfer())
                    ->fromArray($productMeasurementUnitTransfer->toArray(), true)
                    ->toArray()
            );

        $this->productMeasurementUnitStorageEntityManager->saveProductMeasurementUnitStorageEntity($productMeasurementUnitStorageEntity);
    }

    /**
     * @param \Generated\Shared\Transfer\SpyProductMeasurementUnitStorageEntityTransfer[] $mappedProductMeasurementUnitStorageEntities
     * @param int $idProductMeasurementUnit
     *
     * @return \Generated\Shared\Transfer\SpyProductMeasurementUnitStorageEntityTransfer
     */
    protected function selectStorageEntity(array $mappedProductMeasurementUnitStorageEntities, int $idProductMeasurementUnit): SpyProductMeasurementUnitStorageEntityTransfer
    {
        if (isset($mappedProductMeasurementUnitStorageEntities[$idProductMeasurementUnit])) {
            return $mappedProductMeasurementUnitStorageEntities[$idProductMeasurementUnit];
        }

        return new SpyProductMeasurementUnitStorageEntityTransfer();
    }

    /**
     * @param \Generated\Shared\Transfer\SpyProductMeasurementUnitStorageEntityTransfer[] $productMeasurementUnitStorageEntities
     *
     * @return void
     */
    protected function deleteStorageEntities(array $productMeasurementUnitStorageEntities): void
    {
        foreach ($productMeasurementUnitStorageEntities as $productMeasurementUnitStorageEntity) {
            $this->productMeasurementUnitStorageEntityManager->deleteProductMeasurementUnitStorage(
                $productMeasurementUnitStorageEntity->getIdProductMeasurementUnitStorage()
            );
        }
    }

    /**
     * @param int[] $productMeasurementUnitIds
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitTransfer[]
     */
    protected function findProductMeasurementUnitTransfers(array $productMeasurementUnitIds): array
    {
        return $this->productMeasurementUnitFacade->findProductMeasurementUnitTransfers($productMeasurementUnitIds);
    }

    /**
     * @param int[] $productMeasurementUnitIds
     *
     * @return \Generated\Shared\Transfer\SpyProductMeasurementUnitStorageEntityTransfer[]
     */
    protected function findProductMeasurementUnitStorageEntities(array $productMeasurementUnitIds): array
    {
        return $this->productMeasurementUnitStorageRepository->findProductMeasurementUnitStorageEntities($productMeasurementUnitIds);
    }

    /**
     * @param \Generated\Shared\Transfer\SpyProductMeasurementUnitStorageEntityTransfer[] $productMeasurementUnitStorageEntities
     *
     * @return \Generated\Shared\Transfer\SpyProductMeasurementUnitStorageEntityTransfer[]
     */
    protected function mapProductMeasurementUnitStorageEntities(array $productMeasurementUnitStorageEntities): array
    {
        $mappedProductMeasurementUnitStorageEntities = [];
        foreach ($productMeasurementUnitStorageEntities as $entity) {
            $mappedProductMeasurementUnitStorageEntities[$entity->getFkProductMeasurementUnit()] = $entity;
        }

        return $mappedProductMeasurementUnitStorageEntities;
    }
}
