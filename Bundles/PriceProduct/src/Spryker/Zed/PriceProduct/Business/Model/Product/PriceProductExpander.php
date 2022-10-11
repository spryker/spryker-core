<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Business\Model\Product;

use ArrayObject;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Service\PriceProduct\PriceProductServiceInterface;
use Spryker\Shared\PriceProduct\PriceProductConstants;
use Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToCurrencyFacadeInterface;
use Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToStoreFacadeInterface;
use Spryker\Zed\PriceProduct\PriceProductConfig;

class PriceProductExpander implements PriceProductExpanderInterface
{
    /**
     * @var array<\Spryker\Service\PriceProductExtension\Dependency\Plugin\PriceProductDimensionExpanderStrategyPluginInterface>
     */
    protected $priceProductDimensionExpanderStrategyPlugins;

    /**
     * @var \Spryker\Zed\PriceProduct\PriceProductConfig
     */
    protected $priceProductConfig;

    /**
     * @var \Spryker\Service\PriceProduct\PriceProductServiceInterface
     */
    protected $priceProductService;

    /**
     * @var \Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToCurrencyFacadeInterface
     */
    protected $currencyFacade;

    /**
     * @var \Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @param array<\Spryker\Service\PriceProductExtension\Dependency\Plugin\PriceProductDimensionExpanderStrategyPluginInterface> $priceProductDimensionExpanderStrategyPlugins
     * @param \Spryker\Zed\PriceProduct\PriceProductConfig $priceProductConfig
     * @param \Spryker\Service\PriceProduct\PriceProductServiceInterface $priceProductService
     * @param \Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToCurrencyFacadeInterface $currencyFacade
     * @param \Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToStoreFacadeInterface $priceProductToStoreFacade
     */
    public function __construct(
        array $priceProductDimensionExpanderStrategyPlugins,
        PriceProductConfig $priceProductConfig,
        PriceProductServiceInterface $priceProductService,
        PriceProductToCurrencyFacadeInterface $currencyFacade,
        PriceProductToStoreFacadeInterface $priceProductToStoreFacade
    ) {
        $this->priceProductDimensionExpanderStrategyPlugins = $priceProductDimensionExpanderStrategyPlugins;
        $this->priceProductConfig = $priceProductConfig;
        $this->priceProductService = $priceProductService;
        $this->currencyFacade = $currencyFacade;
        $this->storeFacade = $priceProductToStoreFacade;
    }

    /**
     * @param array<\Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function expandPriceProductTransfers(array $priceProductTransfers): array
    {
        $expandedPriceProductTransfers = [];

        foreach ($priceProductTransfers as $priceProductTransfer) {
            $expandedPriceProductTransfers[] = $this->expandPriceProductTransfer($priceProductTransfer);
        }

        return $expandedPriceProductTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function mergeProductAbstractPricesIntoProductConcrete(
        ProductConcreteTransfer $productConcreteTransfer,
        ProductAbstractTransfer $productAbstractTransfer
    ): ProductConcreteTransfer {
        $priceProductTransfers = $this->priceProductService->mergeConcreteAndAbstractPrices(
            $productAbstractTransfer->getPrices()->getArrayCopy(),
            $productConcreteTransfer->getPrices()->getArrayCopy(),
        );

        $productConcreteTransfer->setPrices(new ArrayObject($priceProductTransfers));

        return $productConcreteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function expandPriceProductTransfer(PriceProductTransfer $priceProductTransfer): PriceProductTransfer
    {
        /** @var \Generated\Shared\Transfer\PriceProductDimensionTransfer $priceDimensionTransfer */
        $priceDimensionTransfer = $priceProductTransfer->requirePriceDimension()->getPriceDimension();
        $priceProductTransfer->setPriceDimension($this->expandPriceProductDimensionTransfer($priceDimensionTransfer));

        $currencyTransfer = $priceProductTransfer->getMoneyValueOrFail()->getCurrencyOrFail();
        $priceProductTransfer->getMoneyValueOrFail()->setCurrency($this->getCurrencyTransfer($currencyTransfer));

        $priceProductTransfer->setGroupKey($this->priceProductService->buildPriceProductGroupKey($priceProductTransfer));

        $priceProductTransfer->setMoneyValue(
            $this->expandMoneyValue($priceProductTransfer->getMoneyValueOrFail()),
        );

        return $priceProductTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductDimensionTransfer $priceProductDimensionTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductDimensionTransfer
     */
    protected function expandPriceProductDimensionTransfer(PriceProductDimensionTransfer $priceProductDimensionTransfer): PriceProductDimensionTransfer
    {
        foreach ($this->priceProductDimensionExpanderStrategyPlugins as $priceProductDimensionExpanderStrategyPlugin) {
            if ($priceProductDimensionExpanderStrategyPlugin->isApplicable($priceProductDimensionTransfer)) {
                return $priceProductDimensionExpanderStrategyPlugin->expand($priceProductDimensionTransfer);
            }
        }

        if ($priceProductDimensionTransfer->getIdPriceProductDefault() !== null) {
            $priceProductDimensionTransfer->setType(PriceProductConstants::PRICE_DIMENSION_DEFAULT);
            $priceProductDimensionTransfer->setName($this->priceProductConfig->getPriceDimensionDefaultName());
        }

        return $priceProductDimensionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    protected function getCurrencyTransfer(CurrencyTransfer $currencyTransfer): CurrencyTransfer
    {
        return $this->currencyFacade->getByIdCurrency($currencyTransfer->getIdCurrencyOrFail());
    }

    /**
     * @param \Generated\Shared\Transfer\MoneyValueTransfer $moneyValue
     *
     * @return \Generated\Shared\Transfer\MoneyValueTransfer
     */
    protected function expandMoneyValue(MoneyValueTransfer $moneyValue): MoneyValueTransfer
    {
        if ($moneyValue->getFkStore() !== null) {
            $moneyValue->setStore($this->storeFacade->getStoreById($moneyValue->getFkStore()));
        }

        return $moneyValue;
    }
}
