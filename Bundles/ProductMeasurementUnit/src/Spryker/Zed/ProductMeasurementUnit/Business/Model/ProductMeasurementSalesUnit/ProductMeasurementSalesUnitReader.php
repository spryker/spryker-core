<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit\Business\Model\ProductMeasurementSalesUnit;

use Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer;
use Spryker\Service\UtilMeasurementUnitConversion\Exception\InvalidMeasurementUnitExchangeException;
use Spryker\Zed\ProductMeasurementUnit\Dependency\Service\ProductMeasurementUnitToUtilMeasurementUnitConversionServiceInterface;
use Spryker\Zed\ProductMeasurementUnit\Persistence\ProductMeasurementUnitRepositoryInterface;

class ProductMeasurementSalesUnitReader implements ProductMeasurementSalesUnitReaderInterface
{
    protected const DEFAULT_EXCHANGE_RATIO = 1;

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
     * @return \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer[]
     */
    public function getProductMeasurementSalesUnitTransfersByIdProduct(int $idProduct): array
    {
        $productMeasurementSalesUnitTransfers = $this->productMeasurementUnitRepository
            ->getProductMeasurementSalesUnitTransfersByIdProduct($idProduct);

        foreach ($productMeasurementSalesUnitTransfers as $productMeasurementSalesUnitTransfer) {
            $this->setDefaults($productMeasurementSalesUnitTransfer);
        }

        return $productMeasurementSalesUnitTransfers;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer[]
     */
    public function getProductMeasurementSalesUnitTransfers(): array
    {
        $productMeasurementSalesUnitTransfers = $this->productMeasurementUnitRepository
            ->getProductMeasurementSalesUnitTransfers();

        foreach ($productMeasurementSalesUnitTransfers as $productMeasurementSalesUnitTransfer) {
            $this->setDefaults($productMeasurementSalesUnitTransfer);
        }

        return $productMeasurementSalesUnitTransfers;
    }

    /**
     * @param int $idProductMeasurementSalesUnit
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer
     */
    public function getProductMeasurementSalesUnitTransfer(int $idProductMeasurementSalesUnit): ProductMeasurementSalesUnitTransfer
    {
        $productMeasurementSalesUnitTransfer = $this->productMeasurementUnitRepository
            ->getProductMeasurementSalesUnitTransfer($idProductMeasurementSalesUnit);

        $this->setDefaults($productMeasurementSalesUnitTransfer);

        return $productMeasurementSalesUnitTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer $productMeasurementSalesUnitTransfer
     *
     * @return void
     */
    protected function setDefaults(ProductMeasurementSalesUnitTransfer $productMeasurementSalesUnitTransfer): void
    {
        if ($productMeasurementSalesUnitTransfer->getPrecision() === null) {
            $productMeasurementSalesUnitTransfer->setPrecision($this->getDefaultPrecision($productMeasurementSalesUnitTransfer));
        }

        if ($productMeasurementSalesUnitTransfer->getConversion() === null) {
            $productMeasurementSalesUnitTransfer->setConversion($this->getDefaultConversion($productMeasurementSalesUnitTransfer));
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer $productMeasurementSalesUnitTransfer
     *
     * @return int
     */
    protected function getDefaultPrecision(ProductMeasurementSalesUnitTransfer $productMeasurementSalesUnitTransfer): int
    {
        $productMeasurementSalesUnitTransfer->getProductMeasurementUnit()->requireDefaultPrecision();

        return $productMeasurementSalesUnitTransfer->getProductMeasurementUnit()->getDefaultPrecision();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer $productMeasurementSalesUnitTransfer
     *
     * @return float
     */
    protected function getDefaultConversion(ProductMeasurementSalesUnitTransfer $productMeasurementSalesUnitTransfer): float
    {
        $productMeasurementBaseUnitTransfer = $this
            ->productMeasurementUnitRepository
            ->getProductMeasurementBaseUnitTransfer($productMeasurementSalesUnitTransfer->getFkProductMeasurementBaseUnit());

        $salesUnitMeasurementUnitCode = $productMeasurementSalesUnitTransfer->getProductMeasurementUnit()->getCode();
        $baseUnitMeasurementUnitCode = $productMeasurementBaseUnitTransfer->getProductMeasurementUnit()->getCode();

        try {
            $exchangeRatio = $this->utilMeasurementUnitConversionService->getMeasurementUnitExchangeRatio(
                $salesUnitMeasurementUnitCode,
                $baseUnitMeasurementUnitCode
            );
        } catch (InvalidMeasurementUnitExchangeException $e) {
            $exchangeRatio = static::DEFAULT_EXCHANGE_RATIO;
        }

        return $exchangeRatio;
    }
}
