<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductPackagingUnit\Expander;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\PersistentCartChangeTransfer;
use Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer;

class ProductPackagingUnitAmountExpander implements ProductPackagingUnitAmountExpanderInterface
{
    protected const PARAM_AMOUNT = 'amount-packaging-unit';
    protected const PARAM_LEAD_SALES_UNIT = 'id-lead-product-measurement-sales-unit';

    /**
     * @param \Generated\Shared\Transfer\PersistentCartChangeTransfer $persistentCartChangeTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\PersistentCartChangeTransfer
     */
    public function expandProductPackagingUnitAmountForPersistentCartChange(PersistentCartChangeTransfer $persistentCartChangeTransfer, array $params = []): PersistentCartChangeTransfer
    {
        foreach ($persistentCartChangeTransfer->getItems() as $itemTransfer) {
            $packagingUnitAmount = $this->getPackagingUnitAmount($params, $itemTransfer->getSku());

            if ($packagingUnitAmount) {
                $itemTransfer->setAmount($packagingUnitAmount);
            }

            $amountSalesUnitId = $this->getAmountSalesUnit($params);

            if ($amountSalesUnitId) {
                $amountSalesUnitTransfer = $this->createSalesUnitTransfer($amountSalesUnitId);
                $itemTransfer->setAmountSalesUnit($amountSalesUnitTransfer);
            }
        }

        return $persistentCartChangeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandProductPackagingUnitAmountForCartChangeRequest(CartChangeTransfer $cartChangeTransfer, array $params = []): CartChangeTransfer
    {
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            $packagingUnitAmount = $this->getPackagingUnitAmount($params, $itemTransfer->getSku());

            if ($packagingUnitAmount) {
                $itemTransfer->setAmount($packagingUnitAmount);
            }

            $amountSalesUnitId = $this->getAmountSalesUnit($params);

            if ($amountSalesUnitId) {
                $amountSalesUnitTransfer = $this->createSalesUnitTransfer($amountSalesUnitId);
                $itemTransfer->setAmountSalesUnit($amountSalesUnitTransfer);
            }
        }

        return $cartChangeTransfer;
    }

    /**
     * @param int $idSalesUnit
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer
     */
    protected function createSalesUnitTransfer(int $idSalesUnit)
    {
        return (new ProductMeasurementSalesUnitTransfer())
            ->setIdProductMeasurementSalesUnit($idSalesUnit);
    }

    /**
     * @param array $params
     * @param string $sku
     *
     * @return int|null
     */
    protected function getPackagingUnitAmount(array $params, string $sku): ?int
    {
        if (empty($params[static::PARAM_AMOUNT][$sku])) {
            return null;
        }

        return (int)$params[static::PARAM_AMOUNT][$sku];
    }

    /**
     * @param array $params
     *
     * @return int|null
     */
    protected function getAmountSalesUnit(array $params): ?int
    {
        if (empty($params[static::PARAM_LEAD_SALES_UNIT])) {
            return null;
        }

        return (int)$params[static::PARAM_LEAD_SALES_UNIT];
    }
}
