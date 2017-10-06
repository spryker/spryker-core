<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Currency\Persistence;

interface CurrencyPersistenceInterface
{

    /**
     * @param string $currencyIsoCode
     *
     * @return void
     */
    public function setCurrentCurrencyIsoCode($currencyIsoCode);

    /**
     * @return string
     */
    public function getCurrentCurrencyIsoCode();

}
