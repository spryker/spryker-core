<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnitStorage\Business\Model;

use Generated\Shared\Transfer\ProductMeasurementUnitStorageTransfer;
use Generated\Shared\Transfer\SpyProductMeasurementUnitEntityTransfer;
use Generated\Shared\Transfer\SpyProductMeasurementUnitStorageEntityTransfer;
use Spryker\Zed\ProductMeasurementUnitStorage\Dependency\Repository\ProductMeasurementUnitStorageToProductMeasurementUnitRepositoryInterface;
use Spryker\Zed\ProductMeasurementUnitStorage\Persistence\ProductMeasurementUnitStorageEntityManagerInterface;
use Spryker\Zed\ProductMeasurementUnitStorage\Persistence\ProductMeasurementUnitStorageRepositoryInterface;

class ProductMeasurementUnitStorageWriter implements ProductMeasurementUnitStorageWriterInterface
{
    /**
     * @var \Spryker\Zed\ProductMeasurementUnitStorage\Dependency\Repository\ProductMeasurementUnitStorageToProductMeasurementUnitRepositoryInterface
     */
    protected $productMeasurementUnitRepository;

    /**
     * @var \Spryker\Zed\ProductMeasurementUnitStorage\Persistence\ProductMeasurementUnitStorageRepositoryInterface
     */
    protected $productMeasurementUnitStorageRepository;

    /**
     * @var \Spryker\Zed\ProductMeasurementUnitStorage\Persistence\ProductMeasurementUnitStorageEntityManagerInterface
     */
    protected $productMeasurementUnitStorageEntityManager;

    /**
     * @param \Spryker\Zed\ProductMeasurementUnitStorage\Dependency\Repository\ProductMeasurementUnitStorageToProductMeasurementUnitRepositoryInterface $productMeasurementUnitRepository
     * @param \Spryker\Zed\ProductMeasurementUnitStorage\Persistence\ProductMeasurementUnitStorageRepositoryInterface $productMeasurementUnitStorageRepository
     * @param \Spryker\Zed\ProductMeasurementUnitStorage\Persistence\ProductMeasurementUnitStorageEntityManagerInterface $productMeasurementUnitStorageEntityManager
     */
    public function __construct(
        ProductMeasurementUnitStorageToProductMeasurementUnitRepositoryInterface $productMeasurementUnitRepository,
        ProductMeasurementUnitStorageRepositoryInterface $productMeasurementUnitStorageRepository,
        ProductMeasurementUnitStorageEntityManagerInterface $productMeasurementUnitStorageEntityManager
    ) {
        $this->productMeasurementUnitRepository = $productMeasurementUnitRepository;
        $this->productMeasurementUnitStorageRepository = $productMeasurementUnitStorageRepository;
        $this->productMeasurementUnitStorageEntityManager = $productMeasurementUnitStorageEntityManager;
    }

    /**
     * @param int[] $productMeasurementUnitIds
     *
     * @return void
     */
    public function publish(array $productMeasurementUnitIds)
    {
        $productMeasurementUnitEntities = $this->getProductMeasurementUnitEntities($productMeasurementUnitIds);
        $productMeasurementUnitStorageEntities = $this->getProductMeasurementUnitStorageEntities($productMeasurementUnitIds);
        $mappedProductMeasurementUnitStorageEntities = $this->mapProductMeasurementUnitStorageEntities($productMeasurementUnitStorageEntities);

        foreach ($productMeasurementUnitEntities as $productMeasurementUnitEntity) {
            $idProductMeasurementUnit = $productMeasurementUnitEntity->getIdProductMeasurementUnit();
            $productMeasurementUnitStorageEntity = $this->selectStorageEntity($mappedProductMeasurementUnitStorageEntities, $idProductMeasurementUnit);

            unset($mappedProductMeasurementUnitStorageEntities[$idProductMeasurementUnit]);

            $this->saveStorageEntity($productMeasurementUnitStorageEntity, $productMeasurementUnitEntity);
        }

        $this->deleteStorageEntities($mappedProductMeasurementUnitStorageEntities);
    }

    /**
     * @param \Generated\Shared\Transfer\SpyProductMeasurementUnitStorageEntityTransfer $productMeasurementUnitStorageEntity
     * @param \Generated\Shared\Transfer\SpyProductMeasurementUnitEntityTransfer $productMeasurementUnitEntity
     *
     * @return void
     */
    protected function saveStorageEntity(
        SpyProductMeasurementUnitStorageEntityTransfer $productMeasurementUnitStorageEntity,
        SpyProductMeasurementUnitEntityTransfer $productMeasurementUnitEntity
    ): void {
        $productMeasurementUnitStorageEntity
            ->setFkProductMeasurementUnit($productMeasurementUnitEntity->getIdProductMeasurementUnit())
            ->setData(
                (new ProductMeasurementUnitStorageTransfer())
                    ->fromArray($productMeasurementUnitEntity->toArray(), true)
                    ->setId($productMeasurementUnitEntity->getIdProductMeasurementUnit())
                    ->toArray()
            );

        $this->productMeasurementUnitStorageEntityManager->saveProductMeasurementUnitStorageEntity($productMeasurementUnitStorageEntity);
    }

    /**
     * @param array $mappedProductMeasurementUnitStorageEntities
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
     * @param array $mappedProductMeasurementUnitStorageEntities
     *
     * @return void
     */
    protected function deleteStorageEntities(array $mappedProductMeasurementUnitStorageEntities): void
    {
        array_walk_recursive(
            $mappedProductMeasurementUnitStorageEntities,
            function (SpyProductMeasurementUnitStorageEntityTransfer $productMeasurementUnitStorageEntity) {
                $this->productMeasurementUnitStorageEntityManager->deleteProductMeasurementUnitStorage(
                    $productMeasurementUnitStorageEntity->getIdProductMeasurementUnitStorage()
                );
            }
        );
    }

    /**
     * @param int[] $productMeasurementUnitIds
     *
     * @return \Generated\Shared\Transfer\SpyProductMeasurementUnitEntityTransfer[]
     */
    protected function getProductMeasurementUnitEntities(array $productMeasurementUnitIds)
    {
        return $this->productMeasurementUnitRepository->getProductMeasurementUnitEntities($productMeasurementUnitIds);
    }

    /**
     * @param int[] $productMeasurementUnitIds
     *
     * @return \Generated\Shared\Transfer\SpyProductMeasurementUnitStorageEntityTransfer[]
     */
    protected function getProductMeasurementUnitStorageEntities(array $productMeasurementUnitIds)
    {
        return $this->productMeasurementUnitStorageRepository->getProductMeasurementUnitStorageEntities($productMeasurementUnitIds);
    }

    /**
     * @param \Generated\Shared\Transfer\SpyProductMeasurementUnitStorageEntityTransfer[] $productMeasurementUnitStorageEntities
     *
     * @return array
     */
    protected function mapProductMeasurementUnitStorageEntities(array $productMeasurementUnitStorageEntities)
    {
        $mappedProductMeasurementUnitStorageEntities = [];
        foreach ($productMeasurementUnitStorageEntities as $entity) {
            $mappedProductMeasurementUnitStorageEntities[$entity->getFkProductMeasurementUnit()] = $entity;
        }

        return $mappedProductMeasurementUnitStorageEntities;
    }
}
