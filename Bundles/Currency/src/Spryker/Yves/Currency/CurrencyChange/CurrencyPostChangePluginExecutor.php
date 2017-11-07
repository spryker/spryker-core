<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Currency\CurrencyChange;

use Spryker\Shared\Currency\Persistence\CurrencyPersistenceInterface;

class CurrencyPostChangePluginExecutor implements CurrencyPostChangePluginExecutorInterface
{
    /**
     * @var \Spryker\Yves\Currency\Dependency\CurrencyPostChangePluginInterface[]
     */
    protected $currencyPostChangePlugins = [];

    /**
     * @var \Spryker\Shared\Currency\Persistence\CurrencyPersistenceInterface
     */
    protected $currencyPersistence;

    /**
     * @param \Spryker\Yves\Currency\Dependency\CurrencyPostChangePluginInterface[] $currencyPostChangePlugins
     * @param \Spryker\Shared\Currency\Persistence\CurrencyPersistenceInterface $currencyPersistence
     */
    public function __construct(
        array $currencyPostChangePlugins,
        CurrencyPersistenceInterface $currencyPersistence
    )
    {
        $this->currencyPostChangePlugins = $currencyPostChangePlugins;
        $this->currencyPersistence = $currencyPersistence;
    }

    /**
     * @param string $currencyIsoCode
     * @param string $previousCurrencyIsoCode
     *
     * @return void
     */
    public function execute($currencyIsoCode, $previousCurrencyIsoCode)
    {
        foreach ($this->currencyPostChangePlugins as $currencyPostChangePlugins) {
            if (!$currencyPostChangePlugins->execute($currencyIsoCode)) {
                $this->currencyPersistence->setCurrentCurrencyIsoCode($previousCurrencyIsoCode);
                return;
            }
        }
    }
}
