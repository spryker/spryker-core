<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\PriceProduct\GroupKeyBuilder;

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

        $priceDimensionTransfer = clone $priceProductTransfer->getPriceDimension();
        /**
         * Since abstract and concrete priduct prices has different `idPriceProductDefault` it should't be included in group key.
         */
        $priceDimensionTransfer->setIdPriceProductDefault(null);

        return array_merge($identifierPaths, array_values($priceDimensionTransfer->toArray()));
    }
}
