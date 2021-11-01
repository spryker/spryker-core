<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartCodesRestApi;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\Kernel\AbstractBundleConfig;
use Spryker\Shared\CartCodesRestApi\CartCodesRestApiConfig as CartCodesRestApiSharedConfig;
use Symfony\Component\HttpFoundation\Response;

class CartCodesRestApiConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    public const RESOURCE_VOUCHERS = 'vouchers';

    /**
     * @var string
     */
    public const RESOURCE_CART_RULES = 'cart-rules';

    /**
     * @var string
     */
    public const RESOURCE_CART_CODES = 'cart-codes';

    /**
     * @uses \Spryker\Glue\CartsRestApi\CartsRestApiConfig::RESOURCE_CARTS
     *
     * @var string
     */
    public const RESOURCE_CARTS = 'carts';

    /**
     * @uses \Spryker\Glue\CartsRestApi\CartsRestApiConfig::RESOURCE_GUEST_CARTS
     *
     * @var string
     */
    public const RESOURCE_GUEST_CARTS = 'guest-carts';

    /**
     * @uses \Spryker\Glue\CartsRestApi\CartsRestApiConfig::RESPONSE_CODE_CART_NOT_FOUND
     *
     * @var string
     */
    public const RESPONSE_CODE_CART_NOT_FOUND = '101';

    /**
     * @var string
     */
    public const RESPONSE_CODE_CART_CODE_NOT_FOUND = '3301';

    /**
     * @var string
     */
    public const RESPONSE_CODE_CART_CODE_CANT_BE_ADDED = '3302';

    /**
     * @var string
     */
    public const RESPONSE_CODE_CART_CODE_CANNOT_BE_REMOVED = '3303';

    /**
     * @uses \Spryker\Glue\CartsRestApi\CartsRestApiConfig::EXCEPTION_MESSAGE_CART_WITH_ID_NOT_FOUND
     *
     * @var string
     */
    public const EXCEPTION_MESSAGE_CART_WITH_ID_NOT_FOUND = 'Cart with given uuid not found.';

    /**
     * @var string
     */
    public const EXCEPTION_MESSAGE_CART_CODE_NOT_FOUND = 'Cart code not found in cart.';

    /**
     * @var string
     */
    public const EXCEPTION_MESSAGE_CART_CODE_CANT_BE_ADDED = 'Cart code can\'t be added.';

    /**
     * @var string
     */
    public const EXCEPTION_MESSAGE_CART_CODE_CANNOT_BE_REMOVED = 'Cart code can\'t be removed.';

    /**
     * @api
     *
     * @return array<string, array<string, mixed>>
     */
    public function getErrorIdentifierToRestErrorMapping(): array
    {
        return [
            CartCodesRestApiSharedConfig::ERROR_IDENTIFIER_CART_NOT_FOUND => [
                RestErrorMessageTransfer::CODE => static::RESPONSE_CODE_CART_NOT_FOUND,
                RestErrorMessageTransfer::STATUS => Response::HTTP_NOT_FOUND,
                RestErrorMessageTransfer::DETAIL => static::EXCEPTION_MESSAGE_CART_WITH_ID_NOT_FOUND,
            ],
            CartCodesRestApiSharedConfig::ERROR_IDENTIFIER_CART_CODE_CANT_BE_ADDED => [
                RestErrorMessageTransfer::CODE => static::RESPONSE_CODE_CART_CODE_CANT_BE_ADDED,
                RestErrorMessageTransfer::STATUS => Response::HTTP_UNPROCESSABLE_ENTITY,
                RestErrorMessageTransfer::DETAIL => static::EXCEPTION_MESSAGE_CART_CODE_CANT_BE_ADDED,
            ],
            CartCodesRestApiSharedConfig::ERROR_IDENTIFIER_CART_CODE_NOT_FOUND => [
                RestErrorMessageTransfer::CODE => static::RESPONSE_CODE_CART_CODE_NOT_FOUND,
                RestErrorMessageTransfer::STATUS => Response::HTTP_UNPROCESSABLE_ENTITY,
                RestErrorMessageTransfer::DETAIL => static::EXCEPTION_MESSAGE_CART_CODE_NOT_FOUND,
            ],
            CartCodesRestApiSharedConfig::ERROR_IDENTIFIER_CART_CODE_CANNOT_BE_REMOVED => [
                RestErrorMessageTransfer::CODE => static::RESPONSE_CODE_CART_CODE_CANNOT_BE_REMOVED,
                RestErrorMessageTransfer::STATUS => Response::HTTP_UNPROCESSABLE_ENTITY,
                RestErrorMessageTransfer::DETAIL => static::EXCEPTION_MESSAGE_CART_CODE_CANNOT_BE_REMOVED,
            ],
        ];
    }
}
