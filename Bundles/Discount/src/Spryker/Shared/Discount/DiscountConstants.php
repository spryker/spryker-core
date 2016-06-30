<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Discount;

interface DiscountConstants
{

    const TYPE_VOUCHER = 'voucher';
    const TYPE_CART_RULE = 'cart_rule';

    /**
     * Types of result type saved in VoucherCreateInfoTransfer.
     */
    const MESSAGE_TYPE_SUCCESS = 'success';
    const MESSAGE_TYPE_ERROR = 'error';

}
