<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\ImportDataValidator;

use Generated\Shared\Transfer\PriceProductScheduleImportTransfer;
use Generated\Shared\Transfer\PriceProductScheduleListImportErrorTransfer;
use Spryker\Zed\PriceProductSchedule\Business\Product\ProductFinderInterface;

class ProductDataValidator extends AbstractImportDataValidator
{
    protected const ERROR_MESSAGE_PRODUCT_CONCRETE_NOT_FOUND = 'Concrete product was not found by provided sku %sku%.';
    protected const ERROR_MESSAGE_PRODUCT_ABSTRACT_NOT_FOUND = 'Abstract product was not found by provided sku %sku%.';
    protected const ERROR_MESSAGE_SKU_NOT_VALID = 'One Product Abstract Sku or Product Concrete Sku must be provided.';

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Business\Product\ProductFinderInterface
     */
    protected $productFinder;

    /**
     * @param \Spryker\Zed\PriceProductSchedule\Business\Product\ProductFinderInterface $productFinder
     */
    public function __construct(
        ProductFinderInterface $productFinder
    ) {
        $this->productFinder = $productFinder;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleListImportErrorTransfer|null
     */
    public function validatePriceProductScheduleImportTransfer(
        PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer
    ): ?PriceProductScheduleListImportErrorTransfer {
        if ($this->isSkuFieldUnique($priceProductScheduleImportTransfer) === false) {
            return $this->createPriceProductScheduleListImportErrorTransfer(
                $priceProductScheduleImportTransfer,
                static::ERROR_MESSAGE_SKU_NOT_VALID
            );
        }

        if ($this->isProductAbstractDataValid($priceProductScheduleImportTransfer) === false) {
            return $this->createPriceProductScheduleListImportErrorTransfer(
                $priceProductScheduleImportTransfer,
                static::ERROR_MESSAGE_PRODUCT_ABSTRACT_NOT_FOUND,
                ['%sku%' => $priceProductScheduleImportTransfer->getSkuProductAbstract()]
            );
        }

        if ($this->isProductConcreteDataValid($priceProductScheduleImportTransfer) === false) {
            return $this->createPriceProductScheduleListImportErrorTransfer(
                $priceProductScheduleImportTransfer,
                static::ERROR_MESSAGE_PRODUCT_CONCRETE_NOT_FOUND,
                ['%sku%' => $priceProductScheduleImportTransfer->getSkuProduct()]
            );
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer
     *
     * @return bool
     */
    protected function isSkuFieldUnique(
        PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer
    ): bool {
        $isBothSkuMissing = $priceProductScheduleImportTransfer->getSkuProductAbstract() === null && $priceProductScheduleImportTransfer->getSkuProduct() === null;
        $isBothSkuProvided = $priceProductScheduleImportTransfer->getSkuProductAbstract() !== null && $priceProductScheduleImportTransfer->getSkuProduct() !== null;

        return ($isBothSkuMissing || $isBothSkuProvided) === false;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer
     *
     * @return bool
     */
    protected function isProductAbstractDataValid(
        PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer
    ): bool {
        if ($priceProductScheduleImportTransfer->getSkuProductAbstract() !== null) {
            $productAbstractId = $this->productFinder
                ->findProductAbstractIdBySku($priceProductScheduleImportTransfer->getSkuProductAbstract());

            if ($productAbstractId === null) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer
     *
     * @return bool
     */
    protected function isProductConcreteDataValid(
        PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer
    ): bool {
        if ($priceProductScheduleImportTransfer->getSkuProduct() !== null) {
            $productConcreteId = $this->productFinder
                ->findProductConcreteIdBySku($priceProductScheduleImportTransfer->getSkuProduct());

            if ($productConcreteId === null) {
                return false;
            }
        }

        return true;
    }
}
