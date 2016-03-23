<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Discount;

interface DiscountConstants
{

    const DEFAULT_VOUCHER_CODE_LENGTH = 6;
    const URL_DISCOUNT_POOL_EDIT = '/discount/pool/edit';

    const PARAM_ID_POOL = 'id-pool';
    const PARAM_ID_DISCOUNT = 'id-discount';

    const PLUGIN_DECISION_RULE_VOUCHER = 'PLUGIN_DECISION_RULE_VOUCHER';
    const PLUGIN_DECISION_RULE_MINIMUM_CART_SUB_TOTAL = 'PLUGIN_DECISION_RULE_MINIMUM_CART_SUB_TOTAL';
    const PLUGIN_COLLECTOR_ITEM = 'PLUGIN_COLLECTOR_ITEM';
    const PLUGIN_COLLECTOR_ITEM_PRODUCT_OPTION = 'PLUGIN_COLLECTOR_ITEM_PRODUCT_OPTION';
    const PLUGIN_COLLECTOR_AGGREGATE = 'PLUGIN_COLLECTOR_AGGREGATE';
    const PLUGIN_COLLECTOR_ORDER_EXPENSE = 'PLUGIN_COLLECTOR_ORDER_EXPENSE';
    const PLUGIN_COLLECTOR_ITEM_EXPENSE = 'PLUGIN_COLLECTOR_ITEM_EXPENSE';
    const PLUGIN_CALCULATOR_PERCENTAGE = 'PLUGIN_CALCULATOR_PERCENTAGE';
    const PLUGIN_CALCULATOR_FIXED = 'PLUGIN_CALCULATOR_FIXED';

    const KEY_VOUCHER_CODE_CONSONANTS = 'consonants';
    const KEY_VOUCHER_CODE_VOWELS = 'vowels';
    const KEY_VOUCHER_CODE_NUMBERS = 'numbers';

    /**
     * Types of result type saved in VoucherCreateInfoTransfer.
     */
    const
        MESSAGE_TYPE_SUCCESS = 'success',
        MESSAGE_TYPE_ERROR = 'error';

}
