<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Business\OptionGroup;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Generated\Shared\Transfer\ProductOptionValueStorePricesRequestTransfer;
use Generated\Shared\Transfer\ProductOptionValueStorePricesResponseTransfer;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionValue;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Shared\ProductOption\ProductOptionConstants;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToCurrencyFacadeInterface;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToPriceFacadeInterface;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToStoreFacadeInterface;

class ProductOptionValuePriceReader implements ProductOptionValuePriceReaderInterface
{
    /**
     * @var string|null
     */
    public const DEFAULT_PRICE = null;

    /**
     * @var \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToCurrencyFacadeInterface
     */
    protected $currencyFacade;

    /**
     * @var \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToPriceFacadeInterface
     */
    protected $priceFacade;

    /**
     * @var string|null
     */
    protected static $netPriceModeIdentifierBuffer;

    /**
     * @var string|null
     */
    protected static $grossPriceModeIdentifierBuffer;

    /**
     * @var \Generated\Shared\Transfer\StoreTransfer|null
     */
    protected static $currentStoreTransferBuffer;

    /**
     * @var array<string> Keys are currency ids, values are currency codes.
     */
    protected static $currencyCodeBuffer = [];

    /**
     * @param \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToCurrencyFacadeInterface $currencyFacade
     * @param \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToPriceFacadeInterface $priceFacade
     */
    public function __construct(
        ProductOptionToCurrencyFacadeInterface $currencyFacade,
        ProductOptionToStoreFacadeInterface $storeFacade,
        ProductOptionToPriceFacadeInterface $priceFacade
    ) {
        $this->currencyFacade = $currencyFacade;
        $this->storeFacade = $storeFacade;
        $this->priceFacade = $priceFacade;
    }

    /**
     * @param \Orm\Zed\ProductOption\Persistence\SpyProductOptionValue $productOptionValueEntity
     * @param string|null $currencyCode
     *
     * @return int|null
     */
    public function getCurrentGrossPrice(SpyProductOptionValue $productOptionValueEntity, ?string $currencyCode = null)
    {
        $priceMap = $this->getCurrencyFilteredPriceMap(
            $productOptionValueEntity->getProductOptionValuePrices(),
            $this->getCurrency($currencyCode)->getIdCurrency(),
        );

        $currentIdStore = $this->storeFacade->getCurrentStore()->getIdStore();
        if (isset($priceMap[$currentIdStore]) && $priceMap[$currentIdStore]->getGrossPrice() !== null) {
            return $priceMap[$currentIdStore]->getGrossPrice();
        }

        if (isset($priceMap[static::DEFAULT_PRICE])) {
            return $priceMap[static::DEFAULT_PRICE]->getGrossPrice();
        }

        return null;
    }

    /**
     * @param \Orm\Zed\ProductOption\Persistence\SpyProductOptionValue $productOptionValueEntity
     * @param string|null $currencyCode
     *
     * @return int|null
     */
    public function getCurrentNetPrice(SpyProductOptionValue $productOptionValueEntity, ?string $currencyCode = null)
    {
        $priceMap = $this->getCurrencyFilteredPriceMap(
            $productOptionValueEntity->getProductOptionValuePrices(),
            $this->getCurrency($currencyCode)->getIdCurrency(),
        );

        $currentIdStore = $this->storeFacade->getCurrentStore()->getIdStore();
        if (isset($priceMap[$currentIdStore]) && $priceMap[$currentIdStore]->getNetPrice() !== null) {
            return $priceMap[$currentIdStore]->getNetPrice();
        }

        if (isset($priceMap[static::DEFAULT_PRICE])) {
            return $priceMap[static::DEFAULT_PRICE]->getNetPrice();
        }

        return null;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\ProductOption\Persistence\SpyProductOptionValuePrice> $priceCollection
     * @param int $idCurrency
     *
     * @return array<\Orm\Zed\ProductOption\Persistence\SpyProductOptionValuePrice> Keys are store ids, values are ProductOptionValuePrice entities.
     */
    protected function getCurrencyFilteredPriceMap(ObjectCollection $priceCollection, $idCurrency)
    {
        $priceMap = [];
        foreach ($priceCollection as $price) {
            if ($price->getFkCurrency() !== $idCurrency) {
                continue;
            }

            $priceMap[$price->getFkStore()] = $price;
        }

        return $priceMap;
    }

    /**
     * @param string|null $currencyCode
     *
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    protected function getCurrency(?string $currencyCode = null): CurrencyTransfer
    {
        return $this->currencyFacade->fromIsoCode($currencyCode ?? $this->currencyFacade->getCurrent()->getCode());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionValueStorePricesRequestTransfer $storePricesRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOptionValueStorePricesResponseTransfer
     */
    public function getStorePrices(ProductOptionValueStorePricesRequestTransfer $storePricesRequestTransfer)
    {
        $currentStoreTransfer = $this->getCurrentStore();
        $storePrices = [];
        $defaultStorePrices = [];

        foreach ($storePricesRequestTransfer->getPrices() as $moneyValueTransfer) {
            if ($moneyValueTransfer->getFkStore() === null) {
                $defaultStorePrices = $this->addPrice($defaultStorePrices, $moneyValueTransfer);

                continue;
            }

            if ($moneyValueTransfer->getFkStore() === $currentStoreTransfer->getIdStore()) {
                $storePrices = $this->addPrice($storePrices, $moneyValueTransfer);
            }
        }

        $storePrices = $this->applyDefaultStorePrices($storePrices, $defaultStorePrices);

        return (new ProductOptionValueStorePricesResponseTransfer())->setStorePrices($storePrices);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionValueStorePricesRequestTransfer $storePricesRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOptionValueStorePricesResponseTransfer
     */
    public function getAllPrices(ProductOptionValueStorePricesRequestTransfer $storePricesRequestTransfer)
    {
        $storePrices = [];
        $defaultStorePrices = [];

        foreach ($storePricesRequestTransfer->getPrices() as $moneyValueTransfer) {
            if ($moneyValueTransfer->getFkStore() === null) {
                $defaultStorePrices = $this->addPrice($defaultStorePrices, $moneyValueTransfer);

                continue;
            }

            $storePrices = $this->addPrice($storePrices, $moneyValueTransfer);
        }

        $storePrices = $this->applyDefaultStorePrices($storePrices, $defaultStorePrices);

        return (new ProductOptionValueStorePricesResponseTransfer())->setStorePrices($storePrices);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionTransfer $productOptionTransfer
     * @param string|null $priceMode
     *
     * @return int|null
     */
    public function resolveUnitPrice(ProductOptionTransfer $productOptionTransfer, ?string $priceMode): ?int
    {
        if (!$priceMode) {
            $priceMode = $this->priceFacade->getDefaultPriceMode();
        }

        if ($priceMode === $this->getGrossPriceModeIdentifier()) {
            return $productOptionTransfer->getUnitGrossPrice();
        }

        return $productOptionTransfer->getUnitNetPrice();
    }

    /**
     * @param array $storePrices
     * @param array $defaultStorePrices
     *
     * @return array
     */
    protected function applyDefaultStorePrices(array $storePrices, array $defaultStorePrices)
    {
        foreach ($defaultStorePrices as $idCurrency => $currencyPrices) {
            if (!isset($storePrices[$idCurrency])) {
                $storePrices[$idCurrency] = $currencyPrices;

                continue;
            }

            $storePrices = $this->setDefaultCurrencyPrice($storePrices, $idCurrency, $this->getGrossPriceModeIdentifier(), $currencyPrices);
            $storePrices = $this->setDefaultCurrencyPrice($storePrices, $idCurrency, $this->getNetPriceModeIdentifier(), $currencyPrices);
        }

        return $storePrices;
    }

    /**
     * @param array $storePrices
     * @param int $idCurrency
     * @param string $priceMode
     * @param array $defaultCurrencyPrices
     *
     * @return array
     */
    protected function setDefaultCurrencyPrice(array $storePrices, $idCurrency, $priceMode, array $defaultCurrencyPrices)
    {
        if (!isset($storePrices[$idCurrency][$priceMode][ProductOptionConstants::AMOUNT])) {
            $storePrices[$idCurrency][$priceMode][ProductOptionConstants::AMOUNT] =
                $defaultCurrencyPrices[$priceMode][ProductOptionConstants::AMOUNT];
        }

        return $storePrices;
    }

    /**
     * @param array $prices
     * @param \Generated\Shared\Transfer\MoneyValueTransfer $moneyValueTransfer
     *
     * @return array
     */
    protected function addPrice(array $prices, MoneyValueTransfer $moneyValueTransfer)
    {
        $prices[$this->getCurrencyCodeById($moneyValueTransfer->getFkCurrency())] = [
            $this->getGrossPriceModeIdentifier() => [
                ProductOptionConstants::AMOUNT => $moneyValueTransfer->getGrossAmount(),
            ],
            $this->getNetPriceModeIdentifier() => [
                ProductOptionConstants::AMOUNT => $moneyValueTransfer->getNetAmount(),
            ],
        ];

        return $prices;
    }

    /**
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    protected function getCurrentStore()
    {
        if (static::$currentStoreTransferBuffer === null) {
            static::$currentStoreTransferBuffer = $this->storeFacade->getCurrentStore();
        }

        return static::$currentStoreTransferBuffer;
    }

    /**
     * @param int $idCurrency
     *
     * @return string
     */
    protected function getCurrencyCodeById($idCurrency)
    {
        if (!isset(static::$currencyCodeBuffer[$idCurrency])) {
            static::$currencyCodeBuffer[$idCurrency] = $this->currencyFacade->getByIdCurrency($idCurrency)->getCode();
        }

        return static::$currencyCodeBuffer[$idCurrency];
    }

    /**
     * @return string
     */
    protected function getNetPriceModeIdentifier()
    {
        if (static::$netPriceModeIdentifierBuffer === null) {
            static::$netPriceModeIdentifierBuffer = $this->priceFacade->getNetPriceModeIdentifier();
        }

        return static::$netPriceModeIdentifierBuffer;
    }

    /**
     * @return string
     */
    protected function getGrossPriceModeIdentifier()
    {
        if (static::$grossPriceModeIdentifierBuffer === null) {
            static::$grossPriceModeIdentifierBuffer = $this->priceFacade->getGrossPriceModeIdentifier();
        }

        return static::$grossPriceModeIdentifierBuffer;
    }
}
