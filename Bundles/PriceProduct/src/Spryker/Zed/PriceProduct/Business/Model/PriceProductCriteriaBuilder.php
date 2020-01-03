<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Business\Model;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\PriceProduct\Business\Model\PriceType\PriceProductTypeReaderInterface;
use Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToCurrencyFacadeInterface;
use Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToPriceFacadeInterface;
use Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToStoreFacadeInterface;
use Spryker\Zed\PriceProduct\PriceProductConfig;

class PriceProductCriteriaBuilder implements PriceProductCriteriaBuilderInterface
{
    /**
     * @var \Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToCurrencyFacadeInterface
     */
    protected $currencyFacade;

    /**
     * @var \Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToPriceFacadeInterface
     */
    protected $priceFacade;

    /**
     * @var \Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\PriceProduct\Business\Model\PriceType\PriceProductTypeReaderInterface
     */
    protected $priceProductTypeReader;

    /**
     * @var \Spryker\Zed\PriceProduct\PriceProductConfig
     */
    protected $config;

    /**
     * @var string|null
     */
    protected static $defaultPriceModeCache;

    /**
     * @var \Generated\Shared\Transfer\StoreTransfer|null
     */
    protected static $currentStoreCache;

    /**
     * @var \Generated\Shared\Transfer\CurrencyTransfer|null
     */
    protected static $defaultCurrencyTransferForCurrentStoreCache;

    /**
     * @param \Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToCurrencyFacadeInterface $currencyFacade
     * @param \Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToPriceFacadeInterface $priceFacade
     * @param \Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\PriceProduct\Business\Model\PriceType\PriceProductTypeReaderInterface $priceProductTypeReader
     * @param \Spryker\Zed\PriceProduct\PriceProductConfig $config
     */
    public function __construct(
        PriceProductToCurrencyFacadeInterface $currencyFacade,
        PriceProductToPriceFacadeInterface $priceFacade,
        PriceProductToStoreFacadeInterface $storeFacade,
        PriceProductTypeReaderInterface $priceProductTypeReader,
        PriceProductConfig $config
    ) {
        $this->currencyFacade = $currencyFacade;
        $this->priceFacade = $priceFacade;
        $this->storeFacade = $storeFacade;
        $this->priceProductTypeReader = $priceProductTypeReader;
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductCriteriaTransfer
     */
    public function buildCriteriaFromFilter(PriceProductFilterTransfer $priceProductFilterTransfer): PriceProductCriteriaTransfer
    {
        $priceProductCriteriaTransfer = (new PriceProductCriteriaTransfer())
            ->fromArray($priceProductFilterTransfer->toArray(), true);

        return $priceProductCriteriaTransfer
            ->setPriceDimension(
                $priceProductFilterTransfer->getPriceDimension()
            )
            ->setQuote(
                $priceProductFilterTransfer->getQuote()
            )
            ->setIdCurrency(
                $this->getCurrencyFromFilter($priceProductFilterTransfer)->getIdCurrency()
            )->setIdStore(
                $this->getStoreFromFilter($priceProductFilterTransfer)->getIdStore()
            )->setPriceMode(
                $this->getPriceModeFromFilter($priceProductFilterTransfer)
            )->setPriceType(
                $this->priceProductTypeReader->handleDefaultPriceType($priceProductFilterTransfer->getPriceTypeName())
            );
    }

    /**
     * @param string|null $priceTypeName
     *
     * @return \Generated\Shared\Transfer\PriceProductCriteriaTransfer
     */
    public function buildCriteriaWithDefaultValues($priceTypeName = null): PriceProductCriteriaTransfer
    {
        return (new PriceProductCriteriaTransfer())
        ->setPriceMode(
            $this->priceFacade->getDefaultPriceMode()
        )
        ->setIdCurrency(
            $this->currencyFacade->getDefaultCurrencyForCurrentStore()->getIdCurrency()
        )
        ->setIdStore(
            $this->storeFacade->getCurrentStore()->getIdStore()
        )
        ->setPriceType(
            $this->priceProductTypeReader->handleDefaultPriceType($priceTypeName)
        )
        ->setPriceDimension(
            (new PriceProductDimensionTransfer())->setType($this->config->getPriceDimensionDefault())
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceFilterTransfer
     *
     * @return string
     */
    protected function getPriceModeFromFilter(PriceProductFilterTransfer $priceFilterTransfer): string
    {
        $priceMode = $priceFilterTransfer->getPriceMode();
        if (!$priceMode) {
            return $this->getDefaultPriceMode();
        }

        return $priceMode;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    protected function getCurrencyFromFilter(PriceProductFilterTransfer $priceFilterTransfer)
    {
        if ($priceFilterTransfer->getCurrencyIsoCode()) {
            return $this->currencyFacade->fromIsoCode($priceFilterTransfer->getCurrencyIsoCode());
        }

        return $this->currencyFacade->getDefaultCurrencyForCurrentStore();
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceFilterTransfer
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    protected function getStoreFromFilter(PriceProductFilterTransfer $priceFilterTransfer)
    {
        if ($priceFilterTransfer->getStoreName()) {
            return $this->storeFacade->getStoreByName($priceFilterTransfer->getStoreName());
        }

        return $this->storeFacade->getCurrentStore();
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer[] $priceProductFilterTransfers
     *
     * @return \Generated\Shared\Transfer\PriceProductCriteriaTransfer[]
     */
    public function buildCriteriaTransfersFromFilterTransfersIndexedBySku(array $priceProductFilterTransfers): array
    {
        $storeTransfers = $this->getStoreTransfersForPriceProductFilters($priceProductFilterTransfers);
        $storeTransfers = $this->indexStoreTransfersByStoreName($storeTransfers);

        $currencyTransfers = $this->getCurrencyTransfersForPriceProductFilters($priceProductFilterTransfers);
        $currencyTransfers = $this->indexCurrencyTransfersByIsoCode($currencyTransfers);

        $priceProductCriteriaTransferIndexedBySku = [];
        foreach ($priceProductFilterTransfers as $priceProductFilterTransfer) {
            $currencyTransfer = $currencyTransfers[$priceProductFilterTransfer->getCurrencyIsoCode()] ?? $this->getDefaultCurrencyForCurrentStore();
            $storeTransfer = $storeTransfers[$priceProductFilterTransfer->getStoreName()] ?? $this->getCurrentStore();

            $priceProductCriteriaTransfer = (new PriceProductCriteriaTransfer())
                ->fromArray($priceProductFilterTransfer->toArray(false), true);

            $priceProductCriteriaTransfer
                ->setPriceDimension(
                    $priceProductFilterTransfer->getPriceDimension()
                )
                ->setQuote(
                    $priceProductFilterTransfer->getQuote()
                )
                ->setIdCurrency(
                    $currencyTransfer->getIdCurrency()
                )->setIdStore(
                    $storeTransfer->getIdStore()
                )->setPriceMode(
                    $this->getPriceModeFromFilter($priceProductFilterTransfer)
                )->setPriceType(
                    $this->priceProductTypeReader->handleDefaultPriceType($priceProductFilterTransfer->getPriceTypeName())
                );

            $priceProductCriteriaTransferIndexedBySku[$priceProductFilterTransfer->getSku()] = $priceProductCriteriaTransfer;
        }

        return $priceProductCriteriaTransferIndexedBySku;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer[] $priceProductFilterTransfers
     *
     * @return \Generated\Shared\Transfer\StoreTransfer[]
     */
    protected function getStoreTransfersForPriceProductFilters(array $priceProductFilterTransfers): array
    {
        $storeNames = array_map(function (PriceProductFilterTransfer $priceProductFilterTransfer) {
            return $priceProductFilterTransfer->getStoreName();
        }, $priceProductFilterTransfers);

        $storeNames = array_filter($storeNames);

        return $this->storeFacade->getStoreTransfersByStoreNames($storeNames);
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer[] $storeTransfers
     *
     * @return \Generated\Shared\Transfer\StoreTransfer[]
     */
    protected function indexStoreTransfersByStoreName(array $storeTransfers): array
    {
        $indexedStoreTransfers = [];
        foreach ($storeTransfers as $storeTransfer) {
            $indexedStoreTransfers[$storeTransfer->getName()] = $storeTransfer;
        }

        return $indexedStoreTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer[] $priceProductFilterTransfers
     *
     * @return \Generated\Shared\Transfer\CurrencyTransfer[]
     */
    protected function getCurrencyTransfersForPriceProductFilters(array $priceProductFilterTransfers): array
    {
        $isoCodes = array_map(function (PriceProductFilterTransfer $priceProductFilterTransfer) {
            return $priceProductFilterTransfer->getCurrencyIsoCode();
        }, $priceProductFilterTransfers);

        $isoCodes = array_filter($isoCodes);

        return $this->currencyFacade->getCurrencyTransfersByIsoCodes($isoCodes);
    }

    /**
     * @param \Generated\Shared\Transfer\CurrencyTransfer[] $currencyTransfers
     *
     * @return \Generated\Shared\Transfer\CurrencyTransfer[]
     */
    protected function indexCurrencyTransfersByIsoCode(array $currencyTransfers): array
    {
        $indexedCurrencyTransfers = [];
        foreach ($currencyTransfers as $currencyTransfer) {
            $indexedCurrencyTransfers[$currencyTransfer->getCode()] = $currencyTransfer;
        }

        return $indexedCurrencyTransfers;
    }

    /**
     * @return string
     */
    protected function getDefaultPriceMode(): string
    {
        if (!static::$defaultPriceModeCache) {
            static::$defaultPriceModeCache = $this->priceFacade->getDefaultPriceMode();
        }

        return static::$defaultPriceModeCache;
    }

    /**
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    protected function getCurrentStore(): StoreTransfer
    {
        if (!static::$currentStoreCache) {
            static::$currentStoreCache = $this->storeFacade->getCurrentStore();
        }

        return static::$currentStoreCache;
    }

    /**
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    protected function getDefaultCurrencyForCurrentStore(): CurrencyTransfer
    {
        if (!static::$defaultCurrencyTransferForCurrentStoreCache) {
            static::$defaultCurrencyTransferForCurrentStoreCache = $this->currencyFacade->getDefaultCurrencyForCurrentStore();
        }

        return static::$defaultCurrencyTransferForCurrentStoreCache;
    }
}
