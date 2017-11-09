<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Business\OptionGroup;

use Orm\Zed\ProductOption\Persistence\SpyProductOptionValue;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionValuePrice;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Shared\ProductOption\ProductOptionConstants;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToCurrencyInterface;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToPriceInterface;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToStoreInterface;

class ProductOptionValuePriceReader implements ProductOptionValuePriceReaderInterface
{
    const DEFAULT_PRICE = null;

    /**
     * @var \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToCurrencyInterface
     */
    protected $currencyFacade;

    /**
     * @var \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToStoreInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToPriceInterface
     */
    protected $priceFacade;

    /**
     * @var string
     */
    protected static $netPriceModeIdentifierCache;

    /**
     * @var string
     */
    protected static $grossPriceModeIdentifierCache;

    /**
     * @var \Generated\Shared\Transfer\StoreTransfer
     */
    protected static $currentStoreTransferCache;

    /**
     * @var string[] Keys are currency ids, values are currency codes.
     */
    protected static $currencyCodeCache = [];

    /**
     * @param \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToCurrencyInterface $currencyFacade
     * @param \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToStoreInterface $storeFacade
     * @param \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToPriceInterface $priceFacade
     */
    public function __construct(
        ProductOptionToCurrencyInterface $currencyFacade,
        ProductOptionToStoreInterface $storeFacade,
        ProductOptionToPriceInterface $priceFacade
    ) {
        $this->currencyFacade = $currencyFacade;
        $this->storeFacade = $storeFacade;
        $this->priceFacade = $priceFacade;
    }

    /**
     * @param \Orm\Zed\ProductOption\Persistence\SpyProductOptionValue $productOptionValueEntity
     *
     * @return int|null
     */
    public function getCurrentGrossPrice(SpyProductOptionValue $productOptionValueEntity)
    {
        $priceMap = $this->getCurrencyFilteredPriceMap(
            $productOptionValueEntity->getProductOptionValuePrices(),
            $this->getCurrentIdCurrency()
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
     *
     * @return int|null
     */
    public function getCurrentNetPrice(SpyProductOptionValue $productOptionValueEntity)
    {
        $priceMap = $this->getCurrencyFilteredPriceMap(
            $productOptionValueEntity->getProductOptionValuePrices(),
            $this->getCurrentIdCurrency()
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
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\ProductOption\Persistence\SpyProductOptionValuePrice[] $priceCollection
     * @param int $idCurrency
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionValuePrice[] Keys are store ids, values are ProductOptionValuePrice entities.
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
     * @return int
     */
    protected function getCurrentIdCurrency()
    {
        $currency = $this->currencyFacade->getCurrent();

        return $this->currencyFacade->fromIsoCode($currency->getCode())->getIdCurrency();
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\ProductOption\Persistence\SpyProductOptionValuePrice[] $priceCollection
     *
     * @return array
     */
    public function getStorePrices(ObjectCollection $priceCollection)
    {
        $currentStoreTransfer = $this->getCurrentStore();
        $storePrices = [];
        $defaultStorePrices = [];

        foreach ($priceCollection as $priceEntity) {
            if ($priceEntity->getFkStore() === null) {
                $defaultStorePrices = $this->addPrice($defaultStorePrices, $priceEntity);

                continue;
            }

            if ($priceEntity->getFkStore() === $currentStoreTransfer->getIdStore()) {
                $storePrices = $this->addPrice($storePrices, $priceEntity);
            }
        }

        return $this->applyDefaultStorePrices($storePrices, $defaultStorePrices);
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
     * @param \Orm\Zed\ProductOption\Persistence\SpyProductOptionValuePrice $priceEntity
     *
     * @return array
     */
    protected function addPrice(array $prices, SpyProductOptionValuePrice $priceEntity)
    {
        $prices[$this->getCurrencyCodeById($priceEntity->getFkCurrency())] = [
            $this->getGrossPriceModeIdentifier() => [
                ProductOptionConstants::AMOUNT => $priceEntity->getGrossPrice(),
            ],
            $this->getNetPriceModeIdentifier() => [
                ProductOptionConstants::AMOUNT => $priceEntity->getNetPrice(),
            ],
        ];

        return $prices;
    }

    /**
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    protected function getCurrentStore()
    {
        if (!isset(static::$currentStoreTransferCache)) {
            static::$currentStoreTransferCache = $this->storeFacade->getCurrentStore();
        }

        return static::$currentStoreTransferCache;
    }

    /**
     * @param int $idCurrency
     *
     * @return string
     */
    protected function getCurrencyCodeById($idCurrency)
    {
        if (!isset(static::$currencyCodeCache[$idCurrency])) {
            static::$currencyCodeCache[$idCurrency] = $this->currencyFacade->getByIdCurrency($idCurrency)->getCode();
        }

        return static::$currencyCodeCache[$idCurrency];
    }

    /**
     * @return string
     */
    protected function getNetPriceModeIdentifier()
    {
        if (!isset(static::$netPriceModeIdentifierCache)) {
            static::$netPriceModeIdentifierCache = $this->priceFacade->getNetPriceModeIdentifier();
        }

        return static::$netPriceModeIdentifierCache;
    }

    /**
     * @return string
     */
    protected function getGrossPriceModeIdentifier()
    {
        if (!isset(static::$grossPriceModeIdentifierCache)) {
            static::$grossPriceModeIdentifierCache = $this->priceFacade->getGrossPriceModeIdentifier();
        }

        return static::$grossPriceModeIdentifierCache;
    }
}
