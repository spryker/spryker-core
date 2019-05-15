<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Discount;

use Spryker\Client\Discount\CartCodeHandler\VoucherCartCodeHandler;
use Spryker\Client\Discount\CartCodeHandler\VoucherCartCodeHandlerInterface;
use Spryker\Client\Kernel\AbstractFactory;

class DiscountFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\Discount\CartCodeHandler\VoucherCartCodeHandlerInterface
     */
    public function createVoucherCartCodeHandler(): VoucherCartCodeHandlerInterface
    {
        return new VoucherCartCodeHandler();
    }
}
