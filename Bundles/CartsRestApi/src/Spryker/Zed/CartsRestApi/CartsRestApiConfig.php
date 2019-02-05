<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartsRestApi;

use Spryker\Shared\CartsRestApi\CartsRestApiConfig as SharedCartsRestApiConfig;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class CartsRestApiConfig extends AbstractBundleConfig
{
    /**
     * @see \Spryker\Zed\PersistentCart\Business\Model\QuoteResolver::GLOSSARY_KEY_QUOTE_NOT_AVAILABLE
     */
    public const GLOSSARY_KEY_QUOTE_NOT_AVAILABLE = 'persistent_cart.error.quote.not_available';

    /**
     * @see \Spryker\Zed\Quote\Business\Validator\QuoteValidator::MESSAGE_STORE_DATA_IS_MISSING
     */
    public const MESSAGE_STORE_DATA_IS_MISSING = 'quote.validation.error.store_is_missing';

    /**
     * @see \Spryker\Zed\PersistentCart\Business\Model\QuoteItemOperation::GLOSSARY_KEY_PERMISSION_FAILED
     */
    public const GLOSSARY_KEY_PERMISSION_FAILED = 'global.permission.failed';

    /**
     * @see \Spryker\Zed\PersistentCart\Business\Model\QuoteDeleter::GLOSSARY_KEY_CAN_NOT_REMOVE_LAST_CART
     */
    public const GLOSSARY_KEY_CAN_NOT_REMOVE_LAST_CART = 'persistent_cart.quote.remove.can_not_remove_last_cart';

    /**
     * @see \Spryker\Zed\Currency\Business\Validator\QuoteValidator::MESSAGE_CURRENCY_DATA_IS_MISSING
     */
    public const MESSAGE_CURRENCY_DATA_IS_MISSING = 'quote.validation.error.currency_mode_is_missing';

    /**
     * @see \Spryker\Zed\Currency\Business\Validator\QuoteValidator::MESSAGE_CURRENCY_DATA_IS_INCORRECT
     */
    public const MESSAGE_CURRENCY_DATA_IS_INCORRECT = 'quote.validation.error.currency_mode_is_incorrect';

    /**
     * @see \Spryker\Zed\Currency\Business\Validator\QuoteValidator::GLOSSARY_KEY_ISO_CODE
     */
    public const GLOSSARY_KEY_ISO_CODE = '{{iso_code}}';

    public const RESPONSE_ERROR_MAP = [
        self::GLOSSARY_KEY_QUOTE_NOT_AVAILABLE => SharedCartsRestApiConfig::RESPONSE_CODE_CART_NOT_FOUND,
        self::GLOSSARY_KEY_PERMISSION_FAILED => SharedCartsRestApiConfig::RESPONSE_CODE_FAILED_DELETING_CART_ITEM,
        self::GLOSSARY_KEY_CAN_NOT_REMOVE_LAST_CART => SharedCartsRestApiConfig::RESPONSE_CODE_FAILED_DELETING_CART,
        self::MESSAGE_STORE_DATA_IS_MISSING => SharedCartsRestApiConfig::RESPONSE_CODE_FAILED_CREATING_CART,
        self::MESSAGE_CURRENCY_DATA_IS_MISSING => SharedCartsRestApiConfig::RESPONSE_CODE_FAILED_CREATING_CART,
        self::MESSAGE_CURRENCY_DATA_IS_INCORRECT => SharedCartsRestApiConfig::RESPONSE_CODE_FAILED_CREATING_CART,
        self::GLOSSARY_KEY_ISO_CODE => SharedCartsRestApiConfig::RESPONSE_CODE_FAILED_CREATING_CART,
    ];
}
