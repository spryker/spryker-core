<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Shared\Money\Builder;

interface MoneyBuilderInterface
{

    /**
     * @param int|float|string $amount
     * @param string null $currency
     *
     * @return \Generated\Shared\Transfer\MoneyTransfer
     */
    public function getMoney($amount, $currency = null);

}
