<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\PriceProduct\IdentifierBuilder;

use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Shared\PriceProduct\PriceProductConstants;

class PriceProductIdentifierBuilder implements PriceProductIdentifierBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return string
     */
    public function buildPriceProductIdentifier(PriceProductTransfer $priceProductTransfer): string
    {
        return implode('-', array_filter($this->getIdentifiersPath($priceProductTransfer)));
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return array
     */
    protected function getIdentifiersPath(PriceProductTransfer $priceProductTransfer): array
    {
        $priceProductTransfer->requireMoneyValue()
            ->requirePriceDimension()
            ->requirePriceTypeName()
            ->getMoneyValue()
            ->requireCurrency()
            ->getCurrency()
            ->requireCode();

        $priceDimensionTransfer = $priceProductTransfer->getPriceDimension();

        $identifierPaths = [
            $priceProductTransfer->getMoneyValue()->getCurrency()->getCode(),
            $priceProductTransfer->getPriceTypeName(),
            $priceProductTransfer->getMoneyValue()->getFkStore(),
        ];

        if ($priceProductTransfer->getPriceType()) {
            $identifierPaths[] = $priceProductTransfer->getPriceType()->getPriceModeConfiguration();
        }

        if ($priceProductTransfer->getPriceDimension()->getIdPriceProductDefault()) {
            $identifierPaths[] = PriceProductConstants::PRICE_DIMENSION_DEFAULT;
        }

        return $identifierPaths;
    }
}
