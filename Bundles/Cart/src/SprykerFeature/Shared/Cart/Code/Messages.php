<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */
interface SprykerFeature_Shared_Cart_Code_Messages
{

    const ERROR_PRODUCT_ADD = 'cart.error.product.add';
    const ERROR_PRODUCT_REMOVE = 'cart.error.product.remove';
    const ERROR_PRODUCT_QUANTITY_CHANGE = 'cart.error.product.quantity.change';

    const SUCCESS_PRODUCT_ADD = 'cart.success.product.add';
    const SUCCESS_PRODUCT_REMOVE = 'cart.success.product.remove';
    const SUCCESS_PRODUCT_QUANTITY_CHANGE = 'cart.success.product.quantity.change';

    const ERROR_COUPON_CODE_ADD = 'cart.error.coupon.code.add';
    const ERROR_COUPON_CODE_REMOVE = 'cart.error.coupon.code.remove';
    const ERROR_COUPON_CODE_REMOVED = 'cart.error.coupon.code.removed';

    const SUCCESS_COUPON_CODE_ADD = 'cart.success.coupon.code.add';
    const SUCCESS_COUPON_CODE_REMOVE = 'cart.success.coupon.code.remove';
    const SUCCESS_COUPON_CODES_CLEAR = 'cart.success.coupon.codes.clear';

    const ERROR_LOAD_PRODUCT = 'cart.error.load.product';
    const ERROR_MAX_ITEMS_EXCEEDED = 'cart.error.max.items.exceeded';
    const ERROR_INVALID_OPTION_SPECIFIED = 'cart.error.invalid.option.specified';

}
