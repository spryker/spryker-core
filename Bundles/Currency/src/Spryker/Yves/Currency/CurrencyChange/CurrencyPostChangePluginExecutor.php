<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Currency\CurrencyChange;

class CurrencyPostChangePluginExecutor implements CurrencyPostChangePluginExecutorInterface
{
    /**
     * @var \Spryker\Yves\Currency\Dependency\CurrencyPostChangePluginInterface[]
     */
    protected $currencyPostChangePlugins = [];

    /**
     * @param \Spryker\Yves\Currency\Dependency\CurrencyPostChangePluginInterface[] $currencyPostChangePlugins
     */
    public function __construct(array $currencyPostChangePlugins)
    {
        $this->currencyPostChangePlugins = $currencyPostChangePlugins;
    }

    /**
     * @param string $currencyIsoCode
     *
     * @return void
     */
    public function execute($currencyIsoCode)
    {
        foreach ($this->currencyPostChangePlugins as $currencyPostChangePlugins) {
            $currencyPostChangePlugins->execute($currencyIsoCode);
        }
    }
}
