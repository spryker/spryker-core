<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Currency\CurrencyChange;

use Generated\Shared\Transfer\CurrencyTransfer;

interface CurrencyPostChangePluginExecutorInterface
{
    /**
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currency
     *
     * @return bool
     */
    public function execute(CurrencyTransfer $currency): bool;
}
