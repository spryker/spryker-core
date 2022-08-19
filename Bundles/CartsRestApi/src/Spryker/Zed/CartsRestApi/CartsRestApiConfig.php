<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartsRestApi;

use Spryker\Shared\CartsRestApi\CartsRestApiConfig as CartsRestApiSharedConfig;
use Spryker\Shared\CartsRestApi\CartsRestApiConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class CartsRestApiConfig extends AbstractBundleConfig
{
    /**
     * @uses \Spryker\Zed\Currency\Business\Validator\QuoteValidator::MESSAGE_CURRENCY_DATA_IS_MISSING
     *
     * @var string
     */
    protected const MESSAGE_CURRENCY_DATA_IS_MISSING = 'quote.validation.error.currency_is_missing';

    /**
     * @uses \Spryker\Zed\Currency\Business\Validator\QuoteValidator::MESSAGE_CURRENCY_DATA_IS_INCORRECT
     *
     * @var string
     */
    protected const MESSAGE_CURRENCY_DATA_IS_INCORRECT = 'quote.validation.error.currency_is_incorrect';

    /**
     * @uses \Spryker\Zed\Price\Business\Validator\QuoteValidator::MESSAGE_PRICE_MODE_DATA_IS_MISSING
     *
     * @var string
     */
    protected const MESSAGE_PRICE_MODE_DATA_IS_MISSING = 'quote.validation.error.price_mode_is_missing';

    /**
     * @uses \Spryker\Zed\Price\Business\Validator\QuoteValidator::MESSAGE_PRICE_MODE_DATA_IS_INCORRECT
     *
     * @var string
     */
    protected const MESSAGE_PRICE_MODE_DATA_IS_INCORRECT = 'quote.validation.error.price_mode_is_incorrect';

    /**
     * @var bool
     */
    protected const IS_QUOTE_CREATION_WHILE_QUOTE_MERGING_ENABLED = false;

    /**
     * @api
     *
     * @return array<string, string>
     */
    public static function getErrorToErrorIdentifierMapping(): array
    {
        return [
            static::MESSAGE_CURRENCY_DATA_IS_MISSING => CartsRestApiSharedConfig::ERROR_IDENTIFIER_CURRENCY_DATA_IS_MISSING,
            static::MESSAGE_CURRENCY_DATA_IS_INCORRECT => CartsRestApiSharedConfig::ERROR_IDENTIFIER_CURRENCY_DATA_IS_INCORRECT,
            static::MESSAGE_PRICE_MODE_DATA_IS_MISSING => CartsRestApiSharedConfig::ERROR_IDENTIFIER_PRICE_MODE_DATA_IS_MISSING,
            static::MESSAGE_PRICE_MODE_DATA_IS_INCORRECT => CartsRestApiSharedConfig::ERROR_IDENTIFIER_PRICE_MODE_DATA_IS_INCORRECT,
        ];
    }

    /**
     * Specification:
     * - Enables reloading of cart items.
     * - Enabling leads to performance decreasing.
     *
     * @api
     *
     * @return bool
     */
    public function isQuoteReloadEnabled(): bool
    {
        return $this->get(CartsRestApiConstants::IS_QUOTE_RELOAD_ENABLED, false);
    }

    /**
     * Specification:
     * - Enables creating customer quote while quote merging if it doesn't exist at Persistence.
     *
     * @api
     *
     * @return bool
     */
    public function isQuoteCreationWhileQuoteMergingEnabled(): bool
    {
        return static::IS_QUOTE_CREATION_WHILE_QUOTE_MERGING_ENABLED;
    }
}
