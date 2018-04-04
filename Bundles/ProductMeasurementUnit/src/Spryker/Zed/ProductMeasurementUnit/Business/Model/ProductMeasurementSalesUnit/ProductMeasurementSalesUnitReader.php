<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit\Business\Model\ProductMeasurementSalesUnit;

use Generated\Shared\Transfer\SpyProductMeasurementSalesUnitEntityTransfer;
use Spryker\Zed\ProductMeasurementUnit\Dependency\Service\ProductMeasurementUnitToUtilMeasurementUnitConversionServiceInterface;
use Spryker\Zed\ProductMeasurementUnit\Persistence\ProductMeasurementUnitRepositoryInterface;

class ProductMeasurementSalesUnitReader implements ProductMeasurementSalesUnitReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductMeasurementUnit\Persistence\ProductMeasurementUnitRepositoryInterface
     */
    protected $productMeasurementUnitRepository;

    /**
     * @var \Spryker\Zed\ProductMeasurementUnit\Dependency\Service\ProductMeasurementUnitToUtilMeasurementUnitConversionServiceInterface
     */
    protected $utilMeasurementUnitConversionService;

    /**
     * @param \Spryker\Zed\ProductMeasurementUnit\Persistence\ProductMeasurementUnitRepositoryInterface $productMeasurementUnitRepository
     * @param \Spryker\Zed\ProductMeasurementUnit\Dependency\Service\ProductMeasurementUnitToUtilMeasurementUnitConversionServiceInterface $utilMeasurementUnitConversionService
     */
    public function __construct(
        ProductMeasurementUnitRepositoryInterface $productMeasurementUnitRepository,
        ProductMeasurementUnitToUtilMeasurementUnitConversionServiceInterface $utilMeasurementUnitConversionService
    ) {
        $this->productMeasurementUnitRepository = $productMeasurementUnitRepository;
        $this->utilMeasurementUnitConversionService = $utilMeasurementUnitConversionService;
    }

    /**
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\SpyProductMeasurementSalesUnitEntityTransfer[]
     */
    public function getProductMeasurementSalesUnitEntitiesByIdProduct(int $idProduct): array
    {
        $productMeasurementSalesUnitEntities = $this->productMeasurementUnitRepository
            ->getProductMeasurementSalesUnitEntitiesByIdProduct($idProduct);

        foreach ($productMeasurementSalesUnitEntities as $productMeasurementSalesUnitEntity) {
            $this->setDefaults($productMeasurementSalesUnitEntity);
        }

        return $productMeasurementSalesUnitEntities;
    }

    /**
     * @param int $idProductMeasurementSalesUnit
     *
     * @return \Generated\Shared\Transfer\SpyProductMeasurementSalesUnitEntityTransfer
     */
    public function getProductMeasurementSalesUnitEntity(int $idProductMeasurementSalesUnit): SpyProductMeasurementSalesUnitEntityTransfer
    {
        $productMeasurementSalesUnitEntity = $this->productMeasurementUnitRepository
            ->getProductMeasurementSalesUnitEntity($idProductMeasurementSalesUnit);

        $this->setDefaults($productMeasurementSalesUnitEntity);

        return $productMeasurementSalesUnitEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyProductMeasurementSalesUnitEntityTransfer $productMeasurementSalesUnitEntity
     *
     * @return void
     */
    protected function setDefaults(SpyProductMeasurementSalesUnitEntityTransfer $productMeasurementSalesUnitEntity): void
    {
        if ($productMeasurementSalesUnitEntity->getPrecision() === null) {
            $productMeasurementSalesUnitEntity->setPrecision($this->getDefaultPrecision($productMeasurementSalesUnitEntity));
        }

        if ($productMeasurementSalesUnitEntity->getConversion() === null) {
            $productMeasurementSalesUnitEntity->setConversion($this->getDefaultConversion($productMeasurementSalesUnitEntity));
        }
    }

    /**
     * @param \Generated\Shared\Transfer\SpyProductMeasurementSalesUnitEntityTransfer $productMeasurementSalesUnitEntity
     *
     * @return int
     */
    protected function getDefaultPrecision(SpyProductMeasurementSalesUnitEntityTransfer $productMeasurementSalesUnitEntity): int
    {
        $productMeasurementSalesUnitEntity->getProductMeasurementUnit()->requireDefaultPrecision();

        return $productMeasurementSalesUnitEntity->getProductMeasurementUnit()->getDefaultPrecision();
    }

    /**
     * @param \Generated\Shared\Transfer\SpyProductMeasurementSalesUnitEntityTransfer $productMeasurementSalesUnitEntity
     *
     * @return float
     */
    protected function getDefaultConversion(SpyProductMeasurementSalesUnitEntityTransfer $productMeasurementSalesUnitEntity): float
    {
        $productMeasurementBaseUnitEntity = $this
            ->productMeasurementUnitRepository
            ->getProductMeasurementBaseUnitEntity($productMeasurementSalesUnitEntity->getFkProductMeasurementBaseUnit());

        $salesUnitMeasurementUnitCode = $productMeasurementSalesUnitEntity->getProductMeasurementUnit()->getCode();
        $baseUnitMeasurementUnitCode = $productMeasurementBaseUnitEntity->getProductMeasurementUnit()->getCode();

        $exchangeRatio = $this->utilMeasurementUnitConversionService->getMeasurementUnitExchangeRatio(
            $salesUnitMeasurementUnitCode,
            $baseUnitMeasurementUnitCode
        );

        return $exchangeRatio;
    }
}
