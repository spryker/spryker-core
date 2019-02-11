<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartsRestApi;

use Spryker\Shared\CartsRestApi\CartsRestApiConfig as CartsRestApiSharedConfig;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class CartsRestApiConfig extends AbstractBundleConfig
{
    /**
     * @uses \Spryker\Zed\PersistentCart\Business\Model\QuoteResolver::GLOSSARY_KEY_QUOTE_NOT_AVAILABLE
     */
    public const GLOSSARY_KEY_QUOTE_NOT_AVAILABLE = 'persistent_cart.error.quote.not_available';

    /**
     * @uses \Spryker\Zed\Quote\Business\Validator\QuoteValidator::MESSAGE_STORE_DATA_IS_MISSING
     */
    public const MESSAGE_STORE_DATA_IS_MISSING = 'quote.validation.error.store_is_missing';

    /**
     * @uses \Spryker\Zed\PersistentCart\Business\Model\QuoteItemOperation::GLOSSARY_KEY_PERMISSION_FAILED
     */
    public const GLOSSARY_KEY_PERMISSION_FAILED = 'global.permission.failed';

    /**
     * @uses \Spryker\Zed\PersistentCart\Business\Model\QuoteDeleter::GLOSSARY_KEY_CAN_NOT_REMOVE_LAST_CART
     */
    public const GLOSSARY_KEY_CAN_NOT_REMOVE_LAST_CART = 'persistent_cart.quote.remove.can_not_remove_last_cart';

    /**
     * @uses \Spryker\Zed\Currency\Business\Validator\QuoteValidator::MESSAGE_CURRENCY_DATA_IS_MISSING
     */
    public const MESSAGE_CURRENCY_DATA_IS_MISSING = 'quote.validation.error.currency_mode_is_missing';

    /**
     * @uses \Spryker\Zed\Currency\Business\Validator\QuoteValidator::MESSAGE_CURRENCY_DATA_IS_INCORRECT
     */
    public const MESSAGE_CURRENCY_DATA_IS_INCORRECT = 'quote.validation.error.currency_mode_is_incorrect';

    /**
     * @uses \Spryker\Zed\Price\Business\Validator\QuoteValidator::MESSAGE_PRICE_MODE_DATA_IS_MISSING
     */
    public const MESSAGE_PRICE_MODE_DATA_IS_MISSING = 'quote.validation.error.price_mode_is_missing';

    /**
     * @uses \Spryker\Zed\Price\Business\Validator\QuoteValidator::MESSAGE_PRICE_MODE_DATA_IS_INCORRECT
     */
    public const MESSAGE_PRICE_MODE_DATA_IS_INCORRECT = 'quote.validation.error.price_mode_is_incorrect';

    public const RESPONSE_ERROR_MAP = [
        self::GLOSSARY_KEY_QUOTE_NOT_AVAILABLE => CartsRestApiSharedConfig::RESPONSE_CODE_CART_NOT_FOUND,
        self::GLOSSARY_KEY_PERMISSION_FAILED => CartsRestApiSharedConfig::EXCEPTION_MESSAGE_PERMISSION_FAILED,
        self::GLOSSARY_KEY_CAN_NOT_REMOVE_LAST_CART => CartsRestApiSharedConfig::RESPONSE_CODE_FAILED_DELETING_CART,
        self::MESSAGE_STORE_DATA_IS_MISSING => CartsRestApiSharedConfig::RESPONSE_CODE_STORE_DATA_IS_MISSING,
        self::MESSAGE_CURRENCY_DATA_IS_MISSING => CartsRestApiSharedConfig::RESPONSE_CODE_CURRENCY_DATA_IS_MISSING,
        self::MESSAGE_CURRENCY_DATA_IS_INCORRECT => CartsRestApiSharedConfig::RESPONSE_CODE_CURRENCY_DATA_IS_INCORRECT,
        self::MESSAGE_PRICE_MODE_DATA_IS_MISSING => CartsRestApiSharedConfig::RESPONSE_CODE_PRICE_MODE_DATA_IS_MISSING,
        self::MESSAGE_PRICE_MODE_DATA_IS_INCORRECT => CartsRestApiSharedConfig::RESPONSE_CODE_PRICE_MODE_DATA_IS_INCORRECT,
    ];
}
