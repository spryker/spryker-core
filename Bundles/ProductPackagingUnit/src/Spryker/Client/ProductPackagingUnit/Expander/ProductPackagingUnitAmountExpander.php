<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductPackagingUnit\Expander;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PersistentCartChangeTransfer;
use Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer;

class ProductPackagingUnitAmountExpander implements ProductPackagingUnitAmountExpanderInterface
{
    protected const PARAM_AMOUNT = 'amount';
    protected const PARAM_AMOUNT_SALES_UNIT = 'amount-id-product-measurement-sales-unit';

    /**
     * @param \Generated\Shared\Transfer\PersistentCartChangeTransfer $persistentCartChangeTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\PersistentCartChangeTransfer
     */
    public function expandAmountForPersistentCartChange(PersistentCartChangeTransfer $persistentCartChangeTransfer, array $params = []): PersistentCartChangeTransfer
    {
        foreach ($persistentCartChangeTransfer->getItems() as $itemTransfer) {
            $amount = $this->findAmount($params, $itemTransfer->getSku());

            if ($amount) {
                $itemTransfer->setAmount($amount);
            }

            $this->updateItemTransferWithAmountSalesUnit($itemTransfer, $params);
        }

        return $persistentCartChangeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandAmountForCartChangeRequest(CartChangeTransfer $cartChangeTransfer, array $params = []): CartChangeTransfer
    {
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            $amount = $this->findAmount($params, $itemTransfer->getSku());

            if ($amount) {
                $itemTransfer->setAmount($amount);
            }

            $this->updateItemTransferWithAmountSalesUnit($itemTransfer, $params);
        }

        return $cartChangeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param array $params
     *
     * @return void
     */
    protected function updateItemTransferWithAmountSalesUnit(ItemTransfer $itemTransfer, array $params): void
    {
        $amountSalesUnitId = $this->findAmountSalesUnitId($params, $itemTransfer->getSku());

        if ($amountSalesUnitId) {
            $amountSalesUnitTransfer = $this->createSalesUnitTransfer($amountSalesUnitId);
            $itemTransfer->setAmountSalesUnit($amountSalesUnitTransfer);
        }
    }

    /**
     * @param int $idProductMeasurementSalesUnit
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer
     */
    protected function createSalesUnitTransfer(int $idProductMeasurementSalesUnit): ProductMeasurementSalesUnitTransfer
    {
        return (new ProductMeasurementSalesUnitTransfer())
            ->setIdProductMeasurementSalesUnit($idProductMeasurementSalesUnit);
    }

    /**
     * @param array $params
     * @param string $sku
     *
     * @return string|null
     */
    protected function findAmount(array $params, string $sku): ?string
    {
        if (!isset($params[static::PARAM_AMOUNT][$sku])) {
            return null;
        }

        return $params[static::PARAM_AMOUNT][$sku];
    }

    /**
     * @param array $params
     * @param string $sku
     *
     * @return int|null
     */
    protected function findAmountSalesUnitId(array $params, string $sku): ?int
    {
        if (!isset($params[static::PARAM_AMOUNT_SALES_UNIT][$sku])) {
            return null;
        }

        return (int)$params[static::PARAM_AMOUNT_SALES_UNIT][$sku];
    }
}
