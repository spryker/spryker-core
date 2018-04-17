<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CurrencyExtension\Dependency;

use Generated\Shared\Transfer\CurrencyTransfer;

interface CurrencyPostChangePluginInterface
{
    /**
     *  Specification:
     *   - Plugin executed when currency is changed.
     *   - Return false if something went wrong.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currency
     *
     * @return bool
     */
    public function execute(CurrencyTransfer $currency): bool;
}
