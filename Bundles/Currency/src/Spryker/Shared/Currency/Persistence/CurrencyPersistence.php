<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Currency\Persistence;

use Spryker\Client\Session\SessionClientInterface;

class CurrencyPersistence implements CurrentPersistenceInterface
{

    const CURRENT_CURRENCY = 'current-currency';

    /**
     * @var \Spryker\Client\Session\SessionClientInterface
     */
    protected $sessionClient;

    /**
     * @param \Spryker\Client\Session\SessionClientInterface $sessionClient
     */
    public function __construct(SessionClientInterface $sessionClient)
    {
        $this->sessionClient = $sessionClient;
    }

    /**
     * @param string $currencyCode
     *
     * @return void
     */
    public function setCurrentCurrency($currencyCode)
    {
        $this->sessionClient->set(static::CURRENT_CURRENCY, $currencyCode);
    }

    /**
     * @return string
     */
    public function getCurrentCurrencyIsoCode()
    {
        return $this->sessionClient->get(static::CURRENT_CURRENCY);
    }

}
