<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Sorter\ComparisonStrategy\PriceProductOfferTableView;

use Generated\Shared\Transfer\PriceProductOfferTableViewTransfer;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\GuiTable\Column\ColumnIdCreatorInterface;

class PriceProductOfferTableViewPriceComparisonStrategy implements PriceProductOfferTableViewComparisonStrategyInterface
{
    /**
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Persistence\ProductOfferMerchantPortalGuiRepository::SUFFIX_PRICE_TYPE_NET
     *
     * @var string
     */
    protected const SUFFIX_PRICE_TYPE_NET = '_net';

    /**
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Persistence\ProductOfferMerchantPortalGuiRepository::SUFFIX_PRICE_TYPE_GROSS
     *
     * @var string
     */
    protected const SUFFIX_PRICE_TYPE_GROSS = '_gross';

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\GuiTable\Column\ColumnIdCreatorInterface
     */
    protected ColumnIdCreatorInterface $columnIdCreator;

    /**
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\GuiTable\Column\ColumnIdCreatorInterface $columnIdCreator
     */
    public function __construct(ColumnIdCreatorInterface $columnIdCreator)
    {
        $this->columnIdCreator = $columnIdCreator;
    }

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
        [$priceTypeName, $moneyValueType] = explode('_', $fieldName);
        $priceKey = $this->columnIdCreator->createPriceKey(
            $priceTypeName,
            $moneyValueType . 'Amount',
        );

        return function (
            PriceProductOfferTableViewTransfer $priceProductOfferTableViewTransfer
        ) use (
            $priceKey
        ) {
            $prices = $priceProductOfferTableViewTransfer->getPrices();

            if (!array_key_exists($priceKey, $prices)) {
                return null;
            }

            return $prices[$priceKey];
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
            '/(%s|%s)$/',
            static::SUFFIX_PRICE_TYPE_GROSS,
            static::SUFFIX_PRICE_TYPE_NET,
        );

        preg_match($pattern, $fieldName, $matches);

        return (bool)$matches;
    }
}
