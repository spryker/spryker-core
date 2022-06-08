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
     * @var array<int, \Spryker\Service\PriceProductExtension\Dependency\Plugin\PreBuildPriceProductGroupKeyPluginInterface>
     */
    protected $preBuildPriceProductGroupKeyPlugins;

    /**
     * @param array<int, \Spryker\Service\PriceProductExtension\Dependency\Plugin\PreBuildPriceProductGroupKeyPluginInterface> $preBuildPriceProductGroupKeyPlugins
     */
    public function __construct(array $preBuildPriceProductGroupKeyPlugins)
    {
        $this->preBuildPriceProductGroupKeyPlugins = $preBuildPriceProductGroupKeyPlugins;
    }

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
     * @return array<mixed>
     */
    protected function getGroupKeyParts(PriceProductTransfer $priceProductTransfer): array
    {
        $clonedPriceProductTransfer = (new PriceProductTransfer())->fromArray(
            $priceProductTransfer->toArray(),
        );

        $clonedPriceProductTransfer->requireMoneyValue()
            ->requirePriceDimension()
            ->requirePriceTypeName();

        /** @var \Generated\Shared\Transfer\MoneyValueTransfer $moneyValueTransfer */
        $moneyValueTransfer = $clonedPriceProductTransfer->requireMoneyValue()->getMoneyValue();
        /** @var \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer */
        $currencyTransfer = $moneyValueTransfer->requireCurrency()->getCurrency();
        $currencyTransfer->requireCode();

        $identifierPaths = [
            $currencyTransfer->getCode(),
            $clonedPriceProductTransfer->getPriceTypeName(),
            $moneyValueTransfer->getFkStore(),
        ];

        if ($clonedPriceProductTransfer->getPriceType()) {
            /** @var \Generated\Shared\Transfer\PriceTypeTransfer $priceTypeTransfer */
            $priceTypeTransfer = $clonedPriceProductTransfer->requirePriceType()->getPriceType();
            $identifierPaths[] = $priceTypeTransfer->getPriceModeConfiguration();
        }

        /** @var \Generated\Shared\Transfer\PriceProductDimensionTransfer $priceProductDimensionTransfer */
        $priceProductDimensionTransfer = $clonedPriceProductTransfer->requirePriceDimension()->getPriceDimension();

        $clonedPriceProductTransfer = $this->executePreBuildPriceProductGroupKeyPlugins($clonedPriceProductTransfer);

        return array_merge($identifierPaths, $this->getPriceDimensionGroupKeys($priceProductDimensionTransfer));
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductDimensionTransfer $priceProductDimensionTransfer
     *
     * @return array<mixed>
     */
    public function getPriceDimensionGroupKeys(PriceProductDimensionTransfer $priceProductDimensionTransfer): array
    {
        $priceDimensionKeys = $priceProductDimensionTransfer->toArray(false, true);

        // Since abstract and concrete product prices has different `idPriceProductDefault` it shouldn't be included in group key.
        unset($priceDimensionKeys[PriceProductDimensionTransfer::ID_PRICE_PRODUCT_DEFAULT]);
        unset($priceDimensionKeys[PriceProductDimensionTransfer::NAME]);

        return array_values($priceDimensionKeys);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function executePreBuildPriceProductGroupKeyPlugins(
        PriceProductTransfer $priceProductTransfer
    ): PriceProductTransfer {
        foreach ($this->preBuildPriceProductGroupKeyPlugins as $preBuildPriceProductGroupKeyPlugin) {
            $priceProductTransfer = $preBuildPriceProductGroupKeyPlugin->preBuild($priceProductTransfer);
        }

        return $priceProductTransfer;
    }
}
