<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\PriceProduct\GroupKeyBuilder;

use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;

class PriceProductGroupKeyBuilder implements PriceProductGroupKeyBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return string
     */
    public function buildPriceProductGroupKey(PriceProductTransfer $priceProductTransfer): string
    {
        return implode('-', array_filter($this->getGroupKeyParts($priceProductTransfer)));
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return array
     */
    protected function getGroupKeyParts(PriceProductTransfer $priceProductTransfer): array
    {
        $priceProductTransfer->requireMoneyValue()
            ->requirePriceDimension()
            ->requirePriceTypeName()
            ->getMoneyValue()
            ->requireCurrency()
            ->getCurrency()
            ->requireCode();

        $identifierPaths = [
            $priceProductTransfer->getMoneyValue()->getCurrency()->getCode(),
            $priceProductTransfer->getPriceTypeName(),
            $priceProductTransfer->getMoneyValue()->getFkStore(),
        ];

        if ($priceProductTransfer->getPriceType()) {
            $identifierPaths[] = $priceProductTransfer->getPriceType()->getPriceModeConfiguration();
        }

        $priceDimension = $priceProductTransfer->getPriceDimension()
            ->toArray(false, true);

        /**
         * Since abstract and concrete product prices has different `idPriceProductDefault` it should't be included in group key.
         */
        unset($priceDimension[PriceProductDimensionTransfer::ID_PRICE_PRODUCT_DEFAULT]);

        return array_merge($identifierPaths, array_values($priceDimension));
    }
}
