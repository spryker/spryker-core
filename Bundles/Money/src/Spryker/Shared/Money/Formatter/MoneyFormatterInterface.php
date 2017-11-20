<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Money\Formatter;

use Generated\Shared\Transfer\MoneyTransfer;

interface MoneyFormatterInterface
{
    /**
     * @param \Generated\Shared\Transfer\MoneyTransfer $moneyTransfer
     *
     * @return string
     */
    public function format(MoneyTransfer $moneyTransfer);
}
