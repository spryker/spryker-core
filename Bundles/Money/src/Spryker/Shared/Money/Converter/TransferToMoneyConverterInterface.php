<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Money\Converter;

use Generated\Shared\Transfer\MoneyTransfer;

interface TransferToMoneyConverterInterface
{

    /**
     * @param \Generated\Shared\Transfer\MoneyTransfer $moneyTransfer
     *
     * @return \Money\Money
     */
    public function convert(MoneyTransfer $moneyTransfer);

}
