<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Currency\Persistence;

interface CurrentPersistenceInterface
{

    /**
     * @param string $currencyCode
     *
     * @return void
     */
    public function setCurrentCurrency($currencyCode);

    /**
     * @return string
     */
    public function getCurrentCurrencyIsoCode();

}
