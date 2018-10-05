<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Currency\Dependency;

/**
 * @deprecated use client instead
 */
interface CurrencyPostChangePluginInterface
{
    /**
     *  Specification:
     *   - Plugin executed when currency is changed.
     *   - Return false if something went wrong.
     *
     * @api
     *
     * @param string $currencyCode
     *
     * @return bool
     */
    public function execute($currencyCode);
}
