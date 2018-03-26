<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit\Business\Model\ProductMeasurementSalesUnit;

use Generated\Shared\Transfer\SpyProductMeasurementSalesUnitEntityTransfer;
use Spryker\Zed\ProductMeasurementUnit\Business\Exception\InvalidProductMeasurementUnitExchangeException;
use Spryker\Zed\ProductMeasurementUnit\Dependency\Service\ProductMeasurementUnitToUtilUnitConversionServiceInterface;
use Spryker\Zed\ProductMeasurementUnit\Persistence\ProductMeasurementUnitRepositoryInterface;

class ProductMeasurementSalesUnitReader implements ProductMeasurementSalesUnitReaderInterface
{
    const ERROR_INVALID_EXCHANGE = 'There is no automatic exchange ratio defined between "%s" and "%s" measurement unit codes.';

    /**
     * @var \Spryker\Zed\ProductMeasurementUnit\Persistence\ProductMeasurementUnitRepositoryInterface
     */
    protected $productMeasurementUnitRepository;

    /**
     * @var \Spryker\Zed\ProductMeasurementUnit\Dependency\Service\ProductMeasurementUnitToUtilUnitConversionServiceInterface
     */
    protected $utilUnitConversionService;

    /**
     * @param \Spryker\Zed\ProductMeasurementUnit\Persistence\ProductMeasurementUnitRepositoryInterface $productMeasurementUnitRepository
     * @param \Spryker\Zed\ProductMeasurementUnit\Dependency\Service\ProductMeasurementUnitToUtilUnitConversionServiceInterface $utilUnitConversionService
     */
    public function __construct(
        ProductMeasurementUnitRepositoryInterface $productMeasurementUnitRepository,
        ProductMeasurementUnitToUtilUnitConversionServiceInterface $utilUnitConversionService
    ) {
        $this->productMeasurementUnitRepository = $productMeasurementUnitRepository;
        $this->utilUnitConversionService = $utilUnitConversionService;
    }

    /**
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\SpyProductMeasurementSalesUnitEntityTransfer[]
     */
    public function getProductMeasurementSalesUnitEntitiesByIdProduct($idProduct)
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
    public function getProductMeasurementSalesUnitEntity($idProductMeasurementSalesUnit)
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
    protected function setDefaults(SpyProductMeasurementSalesUnitEntityTransfer $productMeasurementSalesUnitEntity)
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
    protected function getDefaultPrecision(SpyProductMeasurementSalesUnitEntityTransfer $productMeasurementSalesUnitEntity)
    {
        $productMeasurementSalesUnitEntity->getProductMeasurementUnit()->requireDefaultPrecision();

        return $productMeasurementSalesUnitEntity->getProductMeasurementUnit()->getDefaultPrecision();
    }

    /**
     * @param \Generated\Shared\Transfer\SpyProductMeasurementSalesUnitEntityTransfer $productMeasurementSalesUnitEntity
     *
     * @throws \Spryker\Zed\ProductMeasurementUnit\Business\Exception\InvalidProductMeasurementUnitExchangeException
     *
     * @return float
     */
    protected function getDefaultConversion(SpyProductMeasurementSalesUnitEntityTransfer $productMeasurementSalesUnitEntity)
    {
        $productMeasurementBaseUnitEntity = $this
            ->productMeasurementUnitRepository
            ->getProductMeasurementBaseUnitEntity($productMeasurementSalesUnitEntity->getFkProductMeasurementBaseUnit());

        $salesUnitMeasurementUnitCode = $productMeasurementSalesUnitEntity->getProductMeasurementUnit()->getCode();
        $baseUnitMeasurementUnitCode = $productMeasurementBaseUnitEntity->getProductMeasurementUnit()->getCode();

        $exchangeRatio = $this->utilUnitConversionService->findMeasurementUnitExchangeRatio(
            $salesUnitMeasurementUnitCode,
            $baseUnitMeasurementUnitCode
        );

        if ($exchangeRatio === null) {
            throw new InvalidProductMeasurementUnitExchangeException(
                sprintf(static::ERROR_INVALID_EXCHANGE, $salesUnitMeasurementUnitCode, $baseUnitMeasurementUnitCode)
            );
        }

        return $exchangeRatio;
    }
}
