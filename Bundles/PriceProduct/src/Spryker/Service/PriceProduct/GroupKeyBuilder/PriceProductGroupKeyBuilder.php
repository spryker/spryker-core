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
     * @phpstan-return array<mixed>
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return array
     */
    protected function getGroupKeyParts(PriceProductTransfer $priceProductTransfer): array
    {
        $priceProductTransfer->requireMoneyValue()
            ->requirePriceDimension()
            ->requirePriceTypeName();

        /** @var \Generated\Shared\Transfer\MoneyValueTransfer $moneyValueTransfer */
        $moneyValueTransfer = $priceProductTransfer->requireMoneyValue()->getMoneyValue();
        /** @var \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer */
        $currencyTransfer = $moneyValueTransfer->requireCurrency()->getCurrency();
        $currencyTransfer->requireCode();

        $identifierPaths = [
            $currencyTransfer->getCode(),
            $priceProductTransfer->getPriceTypeName(),
            $moneyValueTransfer->getFkStore(),
        ];

        if ($priceProductTransfer->getPriceType()) {
            /** @var \Generated\Shared\Transfer\PriceTypeTransfer $priceTypeTransfer */
            $priceTypeTransfer = $priceProductTransfer->requirePriceType()->getPriceType();
            $identifierPaths[] = $priceTypeTransfer->getPriceModeConfiguration();
        }

        /** @var \Generated\Shared\Transfer\PriceProductDimensionTransfer $priceProductDimensionTransfer */
        $priceProductDimensionTransfer = $priceProductTransfer->requirePriceDimension()->getPriceDimension();

        return array_merge($identifierPaths, $this->getPriceDimensionGroupKeys($priceProductDimensionTransfer));
    }

    /**
     * @phpstan-return array<mixed>
     *
     * @param \Generated\Shared\Transfer\PriceProductDimensionTransfer $priceProductDimensionTransfer
     *
     * @return array
     */
    public function getPriceDimensionGroupKeys(PriceProductDimensionTransfer $priceProductDimensionTransfer): array
    {
        $priceDimensionKeys = $priceProductDimensionTransfer->toArray(false, true);

        /**
         * Since abstract and concrete product prices has different `idPriceProductDefault` it shouldn't be included in group key.
         */
        unset($priceDimensionKeys[PriceProductDimensionTransfer::ID_PRICE_PRODUCT_DEFAULT]);

        return array_values($priceDimensionKeys);
    }
}
