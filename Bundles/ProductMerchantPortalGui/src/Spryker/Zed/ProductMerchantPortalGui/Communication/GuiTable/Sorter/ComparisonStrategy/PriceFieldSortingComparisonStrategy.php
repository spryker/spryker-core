<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\Sorter\ComparisonStrategy;

use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductTableViewTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;

class PriceFieldSortingComparisonStrategy implements PriceProductSortingComparisonStrategyInterface
{
    /**
     * @var string
     */
    protected const SUFFIX_PRICE_TYPE_NET = 'net';

    /**
     * @var string
     */
    protected const SUFFIX_PRICE_TYPE_GROSS = 'gross';

    /**
     * @param string $fieldName
     *
     * @return bool
     */
    public function isApplicable(string $fieldName): bool
    {
        return $this->isPriceField($fieldName);
    }

    /**
     * @param string $fieldName
     *
     * @return callable
     */
    public function getValueExtractorFunction(string $fieldName): callable
    {
        $fieldSegments = explode('_', $fieldName);

        $priceType = $fieldSegments[0];

        $amountType = '';

        if ($fieldSegments[1] === static::SUFFIX_PRICE_TYPE_GROSS) {
            $amountType = MoneyValueTransfer::GROSS_AMOUNT;
        }

        if ($fieldSegments[1] === static::SUFFIX_PRICE_TYPE_NET) {
            $amountType = MoneyValueTransfer::NET_AMOUNT;
        }

        $priceKey = $this->createPriceKey($priceType, $amountType);

        return function (PriceProductTableViewTransfer $priceProductTableViewTransfer) use ($priceKey) {
            $prices = $priceProductTableViewTransfer->getPrices();

            if (!array_key_exists($priceKey, $prices)) {
                return null;
            }

            return (int)$prices[$priceKey];
        };
    }

    /**
     * @param string $fieldName
     *
     * @return bool
     */
    protected function isPriceField(string $fieldName): bool
    {
        $pattern = sprintf(
            '/(_%s|_%s)$/',
            static::SUFFIX_PRICE_TYPE_GROSS,
            static::SUFFIX_PRICE_TYPE_NET,
        );

        preg_match($pattern, $fieldName, $matches);

        return (bool)$matches;
    }

    /**
     * @param string $pryceTypeName
     * @param string $amountType
     *
     * @return string
     */
    protected function createPriceKey(string $pryceTypeName, string $amountType): string
    {
        return sprintf(
            '%s[%s][%s]',
            $pryceTypeName,
            PriceProductTransfer::MONEY_VALUE,
            $amountType,
        );
    }
}
