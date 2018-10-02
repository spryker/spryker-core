<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Discount;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface DiscountConstants
{
    /**
     * Specification:
     * - Represents the "voucher type" from the discount types.
     *
     * @api
     */
    public const TYPE_VOUCHER = 'voucher';

    /**
     * Specification:
     * - Represents the "cart rule type" from the discount types.
     *
     * @api
     */
    public const TYPE_CART_RULE = 'cart_rule';

    /**
     * Specification:
     * - Success result type saved in VoucherCreateInfoTransfer.
     *
     * @api
     */
    public const MESSAGE_TYPE_SUCCESS = 'success';

    /**
     * Specification:
     * - Error result type saved in VoucherCreateInfoTransfer.
     *
     * @api
     */
    public const MESSAGE_TYPE_ERROR = 'error';

    /**
     * Specification:
     * - Uniquely identifies promotion discount collector strategy type.
     *
     * @api
     */
    public const DISCOUNT_COLLECTOR_STRATEGY_QUERY_STRING = 'query-string';

    /**
     * Specification:
     * - Indicates what type of input is used to enter the amount for calculator.
     * - Default type is single amount input.
     *
     * @api
     */
    public const CALCULATOR_DEFAULT_INPUT_TYPE = 'calculator-default-input-type';

    /**
     * Specification:
     * - Indicates what type of input is used to enter the amount for calculator.
     * - Money type renders the input form for each currency.
     *
     * @api
     */
    public const CALCULATOR_MONEY_INPUT_TYPE = 'calculator-money-input-type';
}
