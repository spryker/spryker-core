<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit\Business\Model;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Orm\Zed\ProductMeasurementUnit\Persistence\SpyProductMeasurementSalesUnit;
use Orm\Zed\ProductMeasurementUnit\Persistence\SpyProductMeasurementSalesUnitQuery;

class ProductMeasurementSalesUnitValidator implements ProductMeasurementSalesUnitValidatorInterface
{
    const FLOAT_PRECISION = 0.0000001;

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function validate(CartChangeTransfer $cartChangeTransfer)
    {
        $cartPreCheckResponseTransfer = new CartPreCheckResponseTransfer();
        $cartPreCheckResponseTransfer->setIsSuccess(true);

        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            if ($this->validateItemTransfer($itemTransfer)) {
                continue;
            }

            $cartPreCheckResponseTransfer->setIsSuccess(false);
            $cartPreCheckResponseTransfer->addMessage((new MessageTransfer())->setValue('Not convertible sales unit'));
        }

        return $cartPreCheckResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function validateItemTransfer(ItemTransfer $itemTransfer)
    {
        if (!$this->isInteger($itemTransfer->getQuantity())) {
            return false;
        }

        $productMeasurementSalesUnitEntity = $this->findProductMeasurementSalesUnitEntity($itemTransfer);
        if ($productMeasurementSalesUnitEntity === null) {
            return true;
        }

        $normalizedSalesUnitQuantity = $this->getNormalizedSalesUnitQuantity($itemTransfer->getQuantity(), $productMeasurementSalesUnitEntity);
        if ($this->isInteger($normalizedSalesUnitQuantity)) {
            return true;
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Orm\Zed\ProductMeasurementUnit\Persistence\SpyProductMeasurementSalesUnit|null
     */
    protected function findProductMeasurementSalesUnitEntity(ItemTransfer $itemTransfer)
    {
        $quantitySalesUnit = $itemTransfer->getQuantitySalesUnit();

        if ($quantitySalesUnit === null) {
            return null;
        }

        $idProductMeasurementSalesUnit = $quantitySalesUnit->getIdProductMeasurementSalesUnit();

        if (!$idProductMeasurementSalesUnit) {
            return null;
        }

        return SpyProductMeasurementSalesUnitQuery::create()
            ->filterByIdProductMeasurementSalesUnit($idProductMeasurementSalesUnit)
            ->leftJoinWithProductMeasurementUnit()
            ->find()
            ->getFirst();
    }

    /**
     * @param float $number
     *
     * @return bool
     */
    protected function isInteger($number)
    {
        if (abs($number - round($number)) < static::FLOAT_PRECISION) {
            return true;
        }

        return false;
    }

    /**
     * @param int $quantity
     * @param \Orm\Zed\ProductMeasurementUnit\Persistence\SpyProductMeasurementSalesUnit $productMeasurementSalesUnitEntity
     *
     * @return float
     */
    protected function getNormalizedSalesUnitQuantity($quantity, SpyProductMeasurementSalesUnit $productMeasurementSalesUnitEntity)
    {
        $normalizedSalesUnitQuantity = $quantity * $productMeasurementSalesUnitEntity->getConversion() * $productMeasurementSalesUnitEntity->getPrecision();

        return $normalizedSalesUnitQuantity;
    }
}
