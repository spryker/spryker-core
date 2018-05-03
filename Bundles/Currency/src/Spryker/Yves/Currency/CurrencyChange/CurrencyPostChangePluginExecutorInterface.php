<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Currency\CurrencyChange;

/**
 * @deprecated use \Spryker\Client\CurrencyExtension\Dependency\CurrencyPostChangePluginInterface instead
 */
interface CurrencyPostChangePluginExecutorInterface
{
    /**
     * @param string $currencyIsoCode
     * @param string $previousCurrencyIsoCode
     *
     * @return bool
     */
    public function execute($currencyIsoCode, $previousCurrencyIsoCode);
}
