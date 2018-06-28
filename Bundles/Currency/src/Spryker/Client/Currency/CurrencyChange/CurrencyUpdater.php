<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Currency\CurrencyChange;

use Spryker\Shared\Currency\Builder\CurrencyBuilderInterface;
use Spryker\Shared\Currency\Persistence\CurrencyPersistenceInterface;

class CurrencyUpdater implements CurrencyUpdaterInterface
{
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
     * CurrencyUpdater constructor.
     *
     * @param \Spryker\Shared\Currency\Builder\CurrencyBuilderInterface $currencyBuilder
     * @param \Spryker\Client\Currency\CurrencyChange\CurrencyPostChangePluginExecutorInterface $currencyPostChangePluginExecutor
     * @param \Spryker\Shared\Currency\Persistence\CurrencyPersistenceInterface $currencyPersistence
     */
    public function __construct(
        CurrencyBuilderInterface $currencyBuilder,
        CurrencyPostChangePluginExecutorInterface $currencyPostChangePluginExecutor,
        CurrencyPersistenceInterface $currencyPersistence
    ) {
        $this->currencyBuilder = $currencyBuilder;
        $this->currencyPostChangePluginExecutor = $currencyPostChangePluginExecutor;
        $this->currencyPersistence = $currencyPersistence;
    }

    /**
     * @param string $currencyIsoCode
     *
     * @return void
     */
    public function setCurrentCurrencyIsoCode(string $currencyIsoCode): void
    {
        $previousCurrencyIsoCode = $this->currencyPersistence->getCurrentCurrencyIsoCode();
        $this->currencyPersistence->setCurrentCurrencyIsoCode($currencyIsoCode);

        if (!$this->currencyPostChangePluginExecutor->execute($this->currencyBuilder->fromIsoCode($currencyIsoCode))) {
            $this->currencyPersistence->setCurrentCurrencyIsoCode($previousCurrencyIsoCode);
        }
    }
}
