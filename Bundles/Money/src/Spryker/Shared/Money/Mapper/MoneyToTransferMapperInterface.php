<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Money\Mapper;

use Money\Money;

interface MoneyToTransferMapperInterface
{
    /**
     * @param \Money\Money $money
     *
     * @return \Generated\Shared\Transfer\MoneyTransfer
     */
    public function convert(Money $money);
}
