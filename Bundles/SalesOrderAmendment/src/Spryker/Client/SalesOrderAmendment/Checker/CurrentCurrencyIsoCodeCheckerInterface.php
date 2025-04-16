<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SalesOrderAmendment\Checker;

use Generated\Shared\Transfer\CurrencyTransfer;

interface CurrentCurrencyIsoCodeCheckerInterface
{
    /**
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return bool
     */
    public function execute(CurrencyTransfer $currencyTransfer): bool;
}
