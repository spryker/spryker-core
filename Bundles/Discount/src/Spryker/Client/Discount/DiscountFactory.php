<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Discount;

use Spryker\Client\Discount\CartCode\VoucherCartCode;
use Spryker\Client\Discount\CartCode\VoucherCartCodeInterface;
use Spryker\Client\Kernel\AbstractFactory;

class DiscountFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\Discount\CartCode\VoucherCartCodeInterface
     */
    public function createVoucherCartCode(): VoucherCartCodeInterface
    {
        return new VoucherCartCode();
    }
}
