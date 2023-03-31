<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Currency\Persistence;

use Spryker\Shared\Currency\Dependency\Client\CurrencyToSessionInterface;

class CurrencyPersistence implements CurrencyPersistenceInterface
{
    /**
     * @var string
     */
    public const CURRENT_CURRENCY_ISO_CODE = 'current-currency-iso-code';

    /**
     * @var \Spryker\Shared\Currency\Dependency\Client\CurrencyToSessionInterface
     */
    protected $sessionClient;

    /**
     * @var string
     */
    protected $defaultIsoCode;

    /**
     * @param \Spryker\Shared\Currency\Dependency\Client\CurrencyToSessionInterface $sessionClient
     * @param string $defaultIsoCode
     */
    public function __construct(CurrencyToSessionInterface $sessionClient, string $defaultIsoCode)
    {
        $this->sessionClient = $sessionClient;
        $this->defaultIsoCode = $defaultIsoCode;
    }

    /**
     * @param string $currencyIsoCode
     *
     * @return void
     */
    public function setCurrentCurrencyIsoCode(string $currencyIsoCode): void
    {
        $this->sessionClient->set(static::CURRENT_CURRENCY_ISO_CODE, $currencyIsoCode);
    }

    /**
     * @return string
     */
    public function getCurrentCurrencyIsoCode(): string
    {
        $currentCurrencyIsoCode = $this->sessionClient->get(static::CURRENT_CURRENCY_ISO_CODE);
        if (!$currentCurrencyIsoCode) {
            return $this->defaultIsoCode;
        }

        return $currentCurrencyIsoCode;
    }
}
