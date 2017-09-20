<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Currency\CurrencyChange;

interface CurrencyPostChangePluginExecutorInterface
{

    /**
     *
     * Specification:
     * - Plugin executed when currency is changed
     *
     * @api
     *
     * @param string $currencyIsoCode
     *
     * @return void
     */
    public function execute($currencyIsoCode);

}
