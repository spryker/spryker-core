<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Money\Mapper;

use Generated\Shared\Transfer\MoneyTransfer;

interface TransferToMoneyMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\MoneyTransfer $moneyTransfer
     *
     * @return \Money\Money
     */
    public function convert(MoneyTransfer $moneyTransfer);
}
