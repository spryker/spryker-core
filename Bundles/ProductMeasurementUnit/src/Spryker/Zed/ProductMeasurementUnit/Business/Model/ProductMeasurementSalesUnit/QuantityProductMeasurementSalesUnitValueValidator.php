<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit\Business\Model\ProductMeasurementSalesUnit;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MessageTransfer;

class QuantityProductMeasurementSalesUnitValueValidator implements QuantityProductMeasurementSalesUnitValueValidatorInterface
{
    const ERROR_INVALID_SALES_UNIT_VALUE = 'The product with SKU "%s" has invalid sales unit value.';

    /**
     * @var \Spryker\Zed\ProductMeasurementUnit\Business\Model\ProductMeasurementSalesUnit\ProductMeasurementSalesUnitValueInterface
     */
    protected $productMeasurementSalesUnitValue;

    /**
     * @var \Spryker\Zed\ProductMeasurementUnit\Business\Model\ProductMeasurementSalesUnit\ProductMeasurementSalesUnitReaderInterface
     */
    protected $productMeasurementSalesUnitReader;

    /**
     * @param \Spryker\Zed\ProductMeasurementUnit\Business\Model\ProductMeasurementSalesUnit\ProductMeasurementSalesUnitValueInterface $productMeasurementSalesUnitValue
     * @param \Spryker\Zed\ProductMeasurementUnit\Business\Model\ProductMeasurementSalesUnit\ProductMeasurementSalesUnitReaderInterface $productMeasurementSalesUnitReader
     */
    public function __construct(
        ProductMeasurementSalesUnitValueInterface $productMeasurementSalesUnitValue,
        ProductMeasurementSalesUnitReaderInterface $productMeasurementSalesUnitReader
    ) {
        $this->productMeasurementSalesUnitValue = $productMeasurementSalesUnitValue;
        $this->productMeasurementSalesUnitReader = $productMeasurementSalesUnitReader;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function validate(CartChangeTransfer $cartChangeTransfer)
    {
        $cartPreCheckResponseTransfer = (new CartPreCheckResponseTransfer())
            ->setIsSuccess(true);

        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            if (!$itemTransfer->getQuantitySalesUnit()) {
                continue;
            }

            if ($this->isValidItemTransfer($itemTransfer)) {
                continue;
            }

            $this->addInvalidSalesUnitValueViolation($cartPreCheckResponseTransfer, $itemTransfer->getSku());
        }

        return $cartPreCheckResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function isValidItemTransfer(ItemTransfer $itemTransfer)
    {
        $itemTransfer->requireQuantity();
        $itemTransfer->getQuantitySalesUnit()->requireIdProductMeasurementSalesUnit();

        $productMeasurementSalesUnitEntity = $this->productMeasurementSalesUnitReader->getProductMeasurementSalesUnitEntity(
            $itemTransfer->getQuantitySalesUnit()->getIdProductMeasurementSalesUnit()
        );

        $productMeasurementSalesUnitEntity
            ->requireConversion()
            ->requirePrecision();

        return $this->productMeasurementSalesUnitValue->isIntegerSalesUnitValue(
            $itemTransfer->getQuantity(),
            $productMeasurementSalesUnitEntity->getConversion(),
            $productMeasurementSalesUnitEntity->getPrecision()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CartPreCheckResponseTransfer $cartPreCheckResponse
     * @param string $sku
     *
     * @return void
     */
    protected function addInvalidSalesUnitValueViolation(CartPreCheckResponseTransfer $cartPreCheckResponse, $sku)
    {
        $cartPreCheckResponse
            ->setIsSuccess(false)
            ->addMessage(
                (new MessageTransfer())
                    ->setValue(sprintf(static::ERROR_INVALID_SALES_UNIT_VALUE, $sku))
            );
    }
}
