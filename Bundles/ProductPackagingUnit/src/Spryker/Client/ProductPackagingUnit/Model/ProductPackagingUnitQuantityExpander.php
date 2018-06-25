<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductPackagingUnit\Model;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\PersistentCartChangeTransfer;
use Generated\Shared\Transfer\ProductPackagingUnitQuantityTransfer;

class ProductPackagingUnitQuantityExpander implements ProductPackagingUnitQuantityExpanderInterface
{
    protected const PARAM_AMOUNT_PACKAGING_UNIT = 'amount-product-packaging-unit';

    /**
     * @param \Generated\Shared\Transfer\PersistentCartChangeTransfer $persistentCartChangeTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\PersistentCartChangeTransfer
     */
    public function expandproductPackagingUnitQuantityForPersistentCartChange(PersistentCartChangeTransfer $persistentCartChangeTransfer, array $params = []): PersistentCartChangeTransfer
    {
        $packagingUnitAmount = $this->getPackagingUnitAmount($params);
        if (!$packagingUnitAmount) {
            return $persistentCartChangeTransfer;
        }

        foreach ($persistentCartChangeTransfer->getItems() as $itemTransfer) {
            $itemTransfer->setQuantityPackagingUnit(
                $this->createPackagingUnitTransfer($packagingUnitAmount)
            );
        }

        return $persistentCartChangeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandproductPackagingUnitQuantityForCartChangeRequest(CartChangeTransfer $cartChangeTransfer, array $params = []): CartChangeTransfer
    {
        $packagingUnitAmount = $this->getPackagingUnitAmount($params);
        if (!$packagingUnitAmount) {
            return $cartChangeTransfer;
        }

        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            $itemTransfer->setQuantityPackagingUnit(
                $this->createPackagingUnitTransfer($packagingUnitAmount)
            );
        }

        return $cartChangeTransfer;
    }

    /**
     * @param array $params
     *
     * @return int|null
     */
    protected function getPackagingUnitAmount(array $params): ?int
    {
        if (empty($params[static::PARAM_AMOUNT_PACKAGING_UNIT])) {
            return null;
        }

        return (int)$params[static::PARAM_AMOUNT_PACKAGING_UNIT];
    }

    /**
     * @param int $packagingUnitAmount
     *
     * @return \Generated\Shared\Transfer\ProductPackagingUnitQuantityTransfer
     */
    protected function createPackagingUnitTransfer(int $packagingUnitAmount): ProductPackagingUnitQuantityTransfer
    {
        return (new ProductPackagingUnitQuantityTransfer())
            ->setStockAmount($packagingUnitAmount);
    }
}
