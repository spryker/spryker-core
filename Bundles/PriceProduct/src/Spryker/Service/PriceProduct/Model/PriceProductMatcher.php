<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\PriceProduct\Model;

use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\PriceProductFilterTransfer;

class PriceProductMatcher implements PriceProductMatcherInterface
{
    protected const PRICE_NET_MODE = 'NET_MODE';

    /**
     * @var \Spryker\Service\PriceProductExtension\Dependency\Plugin\PriceProductDecisionPluginInterface[]
     */
    protected $priceProductDecisionPlugins = [];

    public function __construct(array $priceProductDecisionPlugins)
    {
        $this->priceProductDecisionPlugins = $priceProductDecisionPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransferCollection
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return int|null
     */
    public function matchPriceValueByPriceProductCriteria(
        array $priceProductTransferCollection,
        PriceProductCriteriaTransfer $priceProductCriteriaTransfer
    ): ?int {
        $priceProductCriteriaTransfer
            ->requirePriceMode()
            ->requirePriceType()
            ->requireIdCurrency();

        if (count($priceProductTransferCollection) === 0) {
            return null;
        }

        foreach ($this->priceProductDecisionPlugins as $priceProductDecisionPlugin) {
            $priceProductTransfer = $priceProductDecisionPlugin->matchPriceByPriceProductCriteria($priceProductTransferCollection, $priceProductCriteriaTransfer);
            if ($priceProductTransfer) {
                return $this->findPriceValueByPriceMode($priceProductTransfer->getMoneyValue(), $priceProductCriteriaTransfer->getPriceMode());
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransferCollection
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return int|null
     */
    public function matchPriceValueByPriceProductFilter(
        array $priceProductTransferCollection,
        PriceProductFilterTransfer $priceProductFilterTransfer
    ): ?int {
        $priceProductFilterTransfer
            ->requirePriceTypeName()
            ->requireCurrencyIsoCode()
            ->requirePriceMode();

        if (count($priceProductTransferCollection) === 0) {
            return null;
        }

        foreach ($this->priceProductDecisionPlugins as $priceProductDecisionPlugin) {
            $priceProductTransfer = $priceProductDecisionPlugin->matchPriceByPriceProductFilter($priceProductTransferCollection, $priceProductFilterTransfer);
            if ($priceProductTransfer) {
                return $this->findPriceValueByPriceMode($priceProductTransfer->getMoneyValue(), $priceProductFilterTransfer->getPriceMode());
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\MoneyValueTransfer $moneyValueTransfer
     * @param string $priceMode
     *
     * @return int|null
     */
    protected function findPriceValueByPriceMode(MoneyValueTransfer $moneyValueTransfer, string $priceMode): ?int
    {
        if ($priceMode === static::PRICE_NET_MODE) {
            return $moneyValueTransfer->getNetAmount();
        }

        return $moneyValueTransfer->getGrossAmount();
    }

    /**
     * @param array $priceProductTransferCollection
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductDimensionTransfer|null
     */
    public function matchPriceProductDimensionByPriceProductFilter(
        array $priceProductTransferCollection,
        PriceProductFilterTransfer $priceProductFilterTransfer
    ): ?PriceProductDimensionTransfer {
        $priceProductFilterTransfer
            ->requirePriceTypeName()
            ->requireCurrencyIsoCode()
            ->requirePriceMode();

        foreach ($this->priceProductDecisionPlugins as $priceProductDecisionPlugin) {
            $priceProductTransfer = $priceProductDecisionPlugin->matchPriceByPriceProductFilter($priceProductTransferCollection, $priceProductFilterTransfer);
            if ($priceProductTransfer) {
                return $priceProductTransfer->getPriceDimension();
            }
        }

        return null;
    }
}
