<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductMeasurementUnit\ProductMeasurementSalesUnit;

use ArrayObject;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\PersistentCartChangeTransfer;
use Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer;
use Spryker\Client\ProductMeasurementUnit\Exception\InvalidItemCountException;

class ProductMeasurementSalesUnitExpander implements ProductMeasurementSalesUnitExpanderInterface
{
    protected const PARAM_ID_SALES_UNIT = 'id-product-measurement-sales-unit';

    /**
     * @param \Generated\Shared\Transfer\PersistentCartChangeTransfer $cartChangeTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\PersistentCartChangeTransfer
     */
    public function expandSingleItemQuantitySalesUnitForPersistentCartChange(PersistentCartChangeTransfer $cartChangeTransfer, array $params = []): PersistentCartChangeTransfer
    {
        $idSalesUnit = $this->getIdSalesUnit($params);
        if ($idSalesUnit < 1) {
            return $cartChangeTransfer;
        }

        $this->assertSingleItem($cartChangeTransfer->getItems());

        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            $itemTransfer->setQuantitySalesUnit(
                $this->createSalesUnitTransfer($idSalesUnit)
            );
        }

        return $cartChangeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandSingleItemQuantitySalesUnitForCartChangeRequest(CartChangeTransfer $cartChangeTransfer, array $params = []): CartChangeTransfer
    {
        $idSalesUnit = $this->getIdSalesUnit($params);
        if ($idSalesUnit < 1) {
            return $cartChangeTransfer;
        }

        $this->assertSingleItem($cartChangeTransfer->getItems());

        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            $itemTransfer->setQuantitySalesUnit(
                $this->createSalesUnitTransfer($idSalesUnit)
            );
        }

        return $cartChangeTransfer;
    }

    /**
     * @param int $idSalesUnit
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer
     */
    protected function createSalesUnitTransfer($idSalesUnit)
    {
        return (new ProductMeasurementSalesUnitTransfer())
            ->setIdProductMeasurementSalesUnit($idSalesUnit);
    }

    /**
     * @param \ArrayObject $itemTransfers
     *
     * @throws \Spryker\Client\ProductMeasurementUnit\Exception\InvalidItemCountException
     *
     * @return void
     */
    protected function assertSingleItem(ArrayObject $itemTransfers)
    {
        if ($itemTransfers->count() > 1) {
            throw new InvalidItemCountException('measurement_units.error.same_sales_unit_for_multiple_items');
        }
    }

    /**
     * @param array $params
     *
     * @return int
     */
    protected function getIdSalesUnit(array $params)
    {
        if (!isset($params[static::PARAM_ID_SALES_UNIT])) {
            return 0;
        }

        return (int)$params[static::PARAM_ID_SALES_UNIT];
    }
}
