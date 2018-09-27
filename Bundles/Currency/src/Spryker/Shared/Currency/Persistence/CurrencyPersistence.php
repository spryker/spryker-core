<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Currency\Persistence;

use Spryker\Shared\Currency\Dependency\Client\CurrencyToSessionInterface;
use Spryker\Shared\Kernel\Store;

class CurrencyPersistence implements CurrencyPersistenceInterface
{
    public const CURRENT_CURRENCY_ISO_CODE = 'current-currency-iso-code';

    /**
     * @var \Spryker\Shared\Currency\Dependency\Client\CurrencyToSessionInterface
     */
    protected $sessionClient;

    /**
     * @var \Spryker\Shared\Kernel\Store
     */
    protected $store;

    /**
     * @param \Spryker\Shared\Currency\Dependency\Client\CurrencyToSessionInterface $sessionClient
     * @param \Spryker\Shared\Kernel\Store $store
     */
    public function __construct(CurrencyToSessionInterface $sessionClient, Store $store)
    {
        $this->sessionClient = $sessionClient;
        $this->store = $store;
    }

    /**
     * @param string $currencyIsoCode
     *
     * @return void
     */
    public function setCurrentCurrencyIsoCode($currencyIsoCode)
    {
        $this->sessionClient->set(static::CURRENT_CURRENCY_ISO_CODE, $currencyIsoCode);
    }

    /**
     * @return string
     */
    public function getCurrentCurrencyIsoCode()
    {
        $currentCurrencyIsoCode = $this->sessionClient->get(static::CURRENT_CURRENCY_ISO_CODE);
        if (!$currentCurrencyIsoCode) {
            return $this->store->getCurrencyIsoCode();
        }

        return $currentCurrencyIsoCode;
    }
}
