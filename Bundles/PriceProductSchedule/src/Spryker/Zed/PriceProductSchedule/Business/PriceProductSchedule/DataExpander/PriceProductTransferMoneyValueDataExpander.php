<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\DataExpander;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\PriceProductExpandResultTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\PriceProductSchedule\Business\Currency\CurrencyFinderInterface;
use Spryker\Zed\PriceProductSchedule\Business\Store\StoreFinderInterface;

class PriceProductTransferMoneyValueDataExpander extends PriceProductTransferAbstractDataExpander
{
    protected const ERROR_MESSAGE_CURRENCY_NOT_FOUND = 'Currency was not found by provided iso code %s';
    protected const ERROR_MESSAGE_STORE_NOT_FOUND = 'Store was not found by provided name %s';

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Business\Store\StoreFinderInterface
     */
    protected $priceProductScheduleStoreFinder;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Business\Currency\CurrencyFinderInterface
     */
    protected $priceProductScheduleCurrencyFinder;

    /**
     * @var array
     */
    protected $storeCache = [];

    /**
     * @var array
     */
    protected $currencyCache = [];

    /**
     * @param \Spryker\Zed\PriceProductSchedule\Business\Store\StoreFinderInterface $priceProductScheduleStoreFinder
     * @param \Spryker\Zed\PriceProductSchedule\Business\Currency\CurrencyFinderInterface $priceProductScheduleCurrencyFinder
     */
    public function __construct(
        StoreFinderInterface $priceProductScheduleStoreFinder,
        CurrencyFinderInterface $priceProductScheduleCurrencyFinder
    ) {
        $this->priceProductScheduleStoreFinder = $priceProductScheduleStoreFinder;
        $this->priceProductScheduleCurrencyFinder = $priceProductScheduleCurrencyFinder;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductExpandResultTransfer
     */
    public function expand(
        PriceProductTransfer $priceProductTransfer
    ): PriceProductExpandResultTransfer {
        $priceProductExpandResultTransfer = (new PriceProductExpandResultTransfer())
            ->setIsSuccess(false);

        $currencyTransfer = $this->findCurrencyByCode((string)$priceProductTransfer->getMoneyValue()->getCurrency()->getCode());

        if ($currencyTransfer === null) {
            $priceProductScheduleImportErrorTransfer = $this->createPriceProductScheduleListImportErrorTransfer(
                sprintf(
                    static::ERROR_MESSAGE_CURRENCY_NOT_FOUND,
                    $priceProductTransfer->getMoneyValue()->getCurrency()->getCode()
                )
            );

            return $priceProductExpandResultTransfer
                ->setError($priceProductScheduleImportErrorTransfer);
        }

        $storeTransfer = $this->findStoreByName((string)$priceProductTransfer->getMoneyValue()->getStore()->getName());

        if ($storeTransfer === null) {
            $priceProductScheduleImportErrorTransfer = $this->createPriceProductScheduleListImportErrorTransfer(
                sprintf(
                    static::ERROR_MESSAGE_STORE_NOT_FOUND,
                    $priceProductTransfer->getMoneyValue()->getStore()->getName()
                )
            );

            return $priceProductExpandResultTransfer
                ->setError($priceProductScheduleImportErrorTransfer);
        }

        $priceProductTransfer->getMoneyValue()
            ->setCurrency($currencyTransfer)
            ->setFkCurrency($currencyTransfer->getIdCurrency())
            ->setFkStore($storeTransfer->getIdStore());

        return $priceProductExpandResultTransfer
            ->setPriceProduct($priceProductTransfer)
            ->setIsSuccess(true);
    }

    /**
     * @param string $currencyCode
     *
     * @return \Generated\Shared\Transfer\CurrencyTransfer|null
     */
    protected function findCurrencyByCode(string $currencyCode): ?CurrencyTransfer
    {
        if (isset($this->currencyCache[$currencyCode])) {
            return $this->currencyCache[$currencyCode];
        }

        $currencyTransfer = $this->priceProductScheduleCurrencyFinder
            ->findCurrencyByIsoCode($currencyCode);

        if ($currencyTransfer === null) {
            return null;
        }

        $this->currencyCache[$currencyCode] = $currencyTransfer;

        return $this->currencyCache[$currencyCode];
    }

    /**
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\StoreTransfer|null
     */
    protected function findStoreByName(string $storeName): ?StoreTransfer
    {
        if (isset($this->storeCache[$storeName])) {
            return $this->storeCache[$storeName];
        }

        $storeTransfer = $this->priceProductScheduleStoreFinder->findStoreByName($storeName);

        if ($storeTransfer === null) {
            return null;
        }

        $this->storeCache[$storeName] = $storeTransfer;

        return $this->storeCache[$storeName];
    }
}
