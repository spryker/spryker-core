<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Currency\Communication\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\StoreTransfer;
use Generated\Shared\Transfer\StoreWithCurrencyTransfer;
use Spryker\Zed\Currency\Business\CurrencyFacadeInterface;

class StoreWithCurrenciesMapper implements StoreWithCurrenciesMapperInterface
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
     * @param \Generated\Shared\Transfer\StoreWithCurrencyTransfer $storeWithCurrencyTransfer
     *
     * @return array
     */
    public function mapStoreWithCurrencyTransferToArrayWithTimezoneText(StoreWithCurrencyTransfer $storeWithCurrencyTransfer): array
    {
        $storeWithCurrencyTransfer->requireStore();
        $storeTransfer = $storeWithCurrencyTransfer->getStore();

        $currencies = $this->collectCurrencies($storeWithCurrencyTransfer->getCurrencies());
        $timezoneText = $this->buildTimezoneText($storeTransfer);

        return [
            static::KEY_CURRENCIES => $currencies,
            static::KEY_STORE => $storeTransfer->toArray(),
            static::KEY_TIMEZONE_TEXT => $timezoneText,
        ];
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\CurrencyTransfer[] $currencyCollection
     *
     * @return array
     */
    protected function collectCurrencies(ArrayObject $currencyCollection): array
    {
        $currencies = [];

        foreach ($currencyCollection as $currencyTransfer) {
            $currencies[] = $currencyTransfer->toArray();
        }

        return $currencies;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return string
     */
    protected function buildTimezoneText(StoreTransfer $storeTransfer): string
    {
        $storeTransfer->requireTimezone();

        return sprintf(static::TIMEZONE_TEXT_PATTERN, $storeTransfer->getTimezone());
    }
}
