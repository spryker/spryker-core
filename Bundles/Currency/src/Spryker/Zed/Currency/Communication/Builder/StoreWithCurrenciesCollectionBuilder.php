<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Currency\Communication\Builder;

use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Currency\Business\CurrencyFacadeInterface;

class StoreWithCurrenciesCollectionBuilder implements StoreWithCurrenciesCollectionBuilderInterface
{
    protected const TIMEZONE_TEXT_PATTERN = 'The timezone used for the scheduled price will be <b>%s</b> as defined on the store selected';
    protected const KEY_CURRENCIES = 'currencies';
    protected const KEY_STORE = 'store';
    protected const KEY_TIMEZONE_TEXT = 'timezoneText';
    /**
     * @var \Spryker\Zed\Currency\Business\CurrencyFacadeInterface
     */
    protected $currencyFacade;

    /**
     * @param \Spryker\Zed\Currency\Business\CurrencyFacadeInterface $currencyFacade
     */
    public function __construct(CurrencyFacadeInterface $currencyFacade)
    {
        $this->currencyFacade = $currencyFacade;
    }

    /**
     * @param int $idStore
     *
     * @return array
     */
    public function buildStoreWithCurrenciesCollectionByStoreId(int $idStore): array
    {
        $storeWithCurrencyCollection = $this->currencyFacade->getAllStoresWithCurrencies();

        $currencies = [];
        $timezoneText = '';
        $store = [];

        foreach ($storeWithCurrencyCollection as $storeWithCurrencyTransfer) {
            $storeWithCurrencyTransfer->requireStore();
            if ($storeWithCurrencyTransfer->getStore()->getIdStore() !== $idStore) {
                continue;
            }
            $storeTransfer = $storeWithCurrencyTransfer->getStore();
            $timezoneText = $this->buildTimezoneText($storeTransfer);
            $store = $storeTransfer->toArray();

            $currencyCollection = $storeWithCurrencyTransfer->getCurrencies();
            foreach ($currencyCollection as $currencyTransfer) {
                $currencies[] = $currencyTransfer->toArray();
            }
        }

        return [
            static::KEY_CURRENCIES => $currencies,
            static::KEY_STORE => $store,
            static::KEY_TIMEZONE_TEXT => $timezoneText,
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return string
     */
    protected function buildTimezoneText(StoreTransfer $storeTransfer): string
    {
        return sprintf(static::TIMEZONE_TEXT_PATTERN, $storeTransfer->getTimezone());
    }
}
