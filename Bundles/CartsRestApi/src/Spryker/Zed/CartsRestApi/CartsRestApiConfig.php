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
     * @uses \Spryker\Zed\Quote\Business\Validator\QuoteValidator::MESSAGE_STORE_DATA_IS_MISSING
     */
    public const MESSAGE_STORE_DATA_IS_MISSING = 'quote.validation.error.store_is_missing';

    /**
     * @uses \Spryker\Zed\Currency\Business\Validator\QuoteValidator::MESSAGE_CURRENCY_DATA_IS_MISSING
     */
    public const MESSAGE_CURRENCY_DATA_IS_MISSING = 'quote.validation.error.currency_is_missing';

    /**
     * @uses \Spryker\Zed\Currency\Business\Validator\QuoteValidator::MESSAGE_CURRENCY_DATA_IS_INCORRECT
     */
    public const MESSAGE_CURRENCY_DATA_IS_INCORRECT = 'quote.validation.error.currency_is_incorrect';

    /**
     * @uses \Spryker\Zed\Price\Business\Validator\QuoteValidator::MESSAGE_PRICE_MODE_DATA_IS_MISSING
     */
    public const MESSAGE_PRICE_MODE_DATA_IS_MISSING = 'quote.validation.error.price_mode_is_missing';

    /**
     * @uses \Spryker\Zed\Price\Business\Validator\QuoteValidator::MESSAGE_PRICE_MODE_DATA_IS_INCORRECT
     */
    public const MESSAGE_PRICE_MODE_DATA_IS_INCORRECT = 'quote.validation.error.price_mode_is_incorrect';

    /**
     * @return array
     */
    public static function getErrorToErrorIdentifierMapping(): array
    {
        return [
            self::MESSAGE_STORE_DATA_IS_MISSING => CartsRestApiSharedConfig::ERROR_IDENTIFIER_STORE_DATA_IS_MISSING,
            self::MESSAGE_CURRENCY_DATA_IS_MISSING => CartsRestApiSharedConfig::ERROR_IDENTIFIER_CURRENCY_DATA_IS_MISSING,
            self::MESSAGE_CURRENCY_DATA_IS_INCORRECT => CartsRestApiSharedConfig::ERROR_IDENTIFIER_CURRENCY_DATA_IS_INCORRECT,
            self::MESSAGE_PRICE_MODE_DATA_IS_MISSING => CartsRestApiSharedConfig::ERROR_IDENTIFIER_PRICE_MODE_DATA_IS_MISSING,
            self::MESSAGE_PRICE_MODE_DATA_IS_INCORRECT => CartsRestApiSharedConfig::ERROR_IDENTIFIER_PRICE_MODE_DATA_IS_INCORRECT,
        ];
    }
}
