<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Currency\CurrencyChange;

use Spryker\Client\Currency\Dependency\Client\CurrencyToStoreClientInterface;
use Spryker\Client\Currency\Exception\CurrencyDoesNotExistException;
use Spryker\Shared\Currency\Builder\CurrencyBuilderInterface;
use Spryker\Shared\Currency\Persistence\CurrencyPersistenceInterface;

class CurrencyUpdater implements CurrencyUpdaterInterface
{
    public const ERROR_CURRENCY_DOES_NOT_EXIST = 'The provided currency has not been found in current store.';
    /**
     * @var \Spryker\Shared\Currency\Builder\CurrencyBuilderInterface
     */
    protected $currencyBuilder;

    /**
     * @var \Spryker\Client\Currency\CurrencyChange\CurrencyPostChangePluginExecutorInterface
     */
    protected $currencyPostChangePluginExecutor;

    /**
     * @var \Spryker\Shared\Currency\Persistence\CurrencyPersistenceInterface
     */
    protected $currencyPersistence;

    /**
     * @var \Spryker\Client\Currency\Dependency\Client\CurrencyToStoreClientInterface
     */
    protected $storeClient;

    /**
     * CurrencyUpdater constructor.
     *
     * @param \Spryker\Shared\Currency\Builder\CurrencyBuilderInterface $currencyBuilder
     * @param \Spryker\Client\Currency\CurrencyChange\CurrencyPostChangePluginExecutorInterface $currencyPostChangePluginExecutor
     * @param \Spryker\Shared\Currency\Persistence\CurrencyPersistenceInterface $currencyPersistence
     * @param \Spryker\Client\Currency\Dependency\Client\CurrencyToStoreClientInterface $storeClient
     */
    public function __construct(
        CurrencyBuilderInterface $currencyBuilder,
        CurrencyPostChangePluginExecutorInterface $currencyPostChangePluginExecutor,
        CurrencyPersistenceInterface $currencyPersistence,
        CurrencyToStoreClientInterface $storeClient
    ) {
        $this->currencyBuilder = $currencyBuilder;
        $this->currencyPostChangePluginExecutor = $currencyPostChangePluginExecutor;
        $this->currencyPersistence = $currencyPersistence;
        $this->storeClient = $storeClient;
    }

    /**
     * @param string $currencyIsoCode
     *
     * @return void
     */
    public function setCurrentCurrencyIsoCode(string $currencyIsoCode): void
    {
        $this->validateCurrency($currencyIsoCode);
        $previousCurrencyIsoCode = $this->currencyPersistence->getCurrentCurrencyIsoCode();
        $this->currencyPersistence->setCurrentCurrencyIsoCode($currencyIsoCode);

        if (!$this->currencyPostChangePluginExecutor->execute($this->currencyBuilder->fromIsoCode($currencyIsoCode))) {
            $this->currencyPersistence->setCurrentCurrencyIsoCode($previousCurrencyIsoCode);
        }
    }

    /**
     * @param string $currencyIsoCode
     *
     * @throws \Spryker\Client\Currency\Exception\CurrencyDoesNotExistException
     *
     * @return void
     */
    protected function validateCurrency(string $currencyIsoCode): void
    {
        $currencyIsoCodes = $this->storeClient->getCurrentStore()->getAvailableCurrencyIsoCodes();
        if (!in_array($currencyIsoCode, $currencyIsoCodes)) {
            throw new CurrencyDoesNotExistException(static::ERROR_CURRENCY_DOES_NOT_EXIST);
        }
    }
}
