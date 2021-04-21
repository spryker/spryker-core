<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SalesReturnsRestApi;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class SalesReturnsRestApiConfig extends AbstractBundleConfig
{
    public const ERROR_IDENTIFIER_FAILED_CREATE_RETURN = 'ERROR_IDENTIFIER_FAILED_CREATE_RETURN';
    public const ERROR_IDENTIFIER_RETURN_NOT_FOUND = 'ERROR_IDENTIFIER_RETURN_NOT_FOUND';
    public const ERROR_IDENTIFIER_MERCHANT_RETURN_ITEMS_FROM_DIFFERENT_MERCHANTS = 'ERROR_IDENTIFIER_MERCHANT_RETURN_ITEMS_FROM_DIFFERENT_MERCHANTS';

    /**
     * @see \Spryker\Zed\MerchantSalesReturn\Business\Model\MerchantReturnValidator::ERROR_MESSAGE_MERCHANT_RETURN_ITEMS_FROM_DIFFERENT_MERCHANTS
     */
    protected const ERROR_MESSAGE_MERCHANT_RETURN_ITEMS_FROM_DIFFERENT_MERCHANTS = 'merchant_sales_return.message.items_from_different_merchant_detected';

    /**
     * @api
     *
     * @return string[]
     */
    public function getErrorMessageToErrorIdentifierMapping(): array
    {
        return [
            static::ERROR_MESSAGE_MERCHANT_RETURN_ITEMS_FROM_DIFFERENT_MERCHANTS => static::ERROR_IDENTIFIER_MERCHANT_RETURN_ITEMS_FROM_DIFFERENT_MERCHANTS,
        ];
    }

    /**
     * @api
     *
     * @return string
     */
    public function getDefaultErrorMessageIdentifier(): string
    {
        return static::ERROR_IDENTIFIER_FAILED_CREATE_RETURN;
    }
}
