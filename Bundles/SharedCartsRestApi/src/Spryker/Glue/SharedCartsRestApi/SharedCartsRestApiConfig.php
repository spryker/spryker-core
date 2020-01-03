<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SharedCartsRestApi;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\Kernel\AbstractBundleConfig;
use Spryker\Shared\SharedCartsRestApi\SharedCartsRestApiConfig as SharedSharedCartsRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class SharedCartsRestApiConfig extends AbstractBundleConfig
{
    public const RESOURCE_SHARED_CARTS = 'shared-carts';
    /**
     * @uses \Spryker\Glue\CartsRestApi\CartsRestApiConfig::RESOURCE_CARTS
     */
    public const RESOURCE_CARTS = 'carts';

    /**
     * @uses \Spryker\Glue\CartsRestApi\CartsRestApiConfig::RESPONSE_CODE_CART_NOT_FOUND
     */
    public const RESPONSE_CODE_CART_NOT_FOUND = '101';
    /**
     * @uses \Spryker\Glue\CartPermissionGroupsRestApi\CartPermissionGroupsRestApiConfig::RESPONSE_CODE_CART_PERMISSION_GROUP_NOT_FOUND
     */
    public const RESPONSE_CODE_CART_PERMISSION_GROUP_NOT_FOUND = '2501';
    /**
     * @uses \Spryker\Glue\CartsRestApi\CartsRestApiConfig::RESPONSE_CODE_CART_ID_MISSING
     */
    public const RESPONSE_CODE_CART_ID_MISSING = '104';
    /**
     * @uses \Spryker\Glue\CompanyUsersRestApi\CompanyUsersRestApiConfig::RESPONSE_CODE_COMPANY_USER_NOT_FOUND
     */
    public const RESPONSE_CODE_COMPANY_USER_NOT_FOUND = '1404';
    public const RESPONSE_CODE_SHARING_CART_FORBIDDEN = '2701';
    public const RESPONSE_CODE_FAILED_TO_SHARE_CART = '2702';
    public const RESPONSE_CODE_SHARE_CART_OUTSIDE_THE_COMPANY_FORBIDDEN = '2703';
    public const RESPONSE_CODE_SHARED_CART_ID_MISSING = '2704';
    public const RESPONSE_CODE_SHARED_CART_NOT_FOUND = '2705';
    public const RESPONSE_CODE_FAILED_TO_SAVE_SHARED_CART = '2706';

    /**
     * @uses \Spryker\Glue\CartsRestApi\CartsRestApiConfig::EXCEPTION_MESSAGE_CART_WITH_ID_NOT_FOUND
     */
    public const EXCEPTION_MESSAGE_CART_WITH_ID_NOT_FOUND = 'Cart with given uuid not found.';
    /**
     * @uses \Spryker\Glue\CartPermissionGroupsRestApi\CartPermissionGroupsRestApiConfig::RESPONSE_DETAIL_CART_PERMISSION_GROUP_NOT_FOUND
     */
    public const RESPONSE_DETAIL_CART_PERMISSION_GROUP_NOT_FOUND = 'Cart permission group not found.';
    /**
     * @uses \Spryker\Glue\CartsRestApi\CartsRestApiConfig::EXCEPTION_MESSAGE_CART_ID_MISSING
     */
    public const EXCEPTION_MESSAGE_CART_ID_MISSING = 'Cart uuid is missing.';
    /**
     * @uses \Spryker\Glue\CompanyUsersRestApi\CompanyUsersRestApiConfig::RESPONSE_DETAIL_COMPANY_USER_NOT_FOUND
     */
    public const RESPONSE_DETAIL_COMPANY_USER_NOT_FOUND = 'Company user not found';
    public const RESPONSE_DETAIL_SHARING_CART_FORBIDDEN = 'Action is forbidden.';
    public const RESPONSE_DETAIL_FAILED_TO_SHARE_CART = 'Failed to share a cart.';
    public const RESPONSE_DETAIL_SHARE_CART_OUTSIDE_THE_COMPANY_FORBIDDEN = 'Cart can be shared only with company users from same company.';
    public const RESPONSE_DETAIL_SHARED_CART_ID_MISSING = 'Shared cart id is missing.';
    public const RESPONSE_DETAIL_SHARED_CART_NOT_FOUND = 'Shared cart not found.';
    public const RESPONSE_DETAIL_FAILED_TO_SAVE_SHARED_CART = 'Failed to save shared cart.';

    /**
     * @return array
     */
    public function getErrorIdentifierToRestErrorMapping(): array
    {
        return [
            SharedSharedCartsRestApiConfig::ERROR_IDENTIFIER_QUOTE_NOT_FOUND => [
                RestErrorMessageTransfer::CODE => static::RESPONSE_CODE_CART_NOT_FOUND,
                RestErrorMessageTransfer::STATUS => Response::HTTP_NOT_FOUND,
                RestErrorMessageTransfer::DETAIL => static::EXCEPTION_MESSAGE_CART_WITH_ID_NOT_FOUND,
            ],
            SharedSharedCartsRestApiConfig::ERROR_IDENTIFIER_SHARED_CART_NOT_FOUND => [
                RestErrorMessageTransfer::CODE => static::RESPONSE_CODE_SHARED_CART_NOT_FOUND,
                RestErrorMessageTransfer::STATUS => Response::HTTP_NOT_FOUND,
                RestErrorMessageTransfer::DETAIL => static::RESPONSE_DETAIL_SHARED_CART_NOT_FOUND,
            ],
            SharedSharedCartsRestApiConfig::ERROR_IDENTIFIER_ACTION_FORBIDDEN => [
                RestErrorMessageTransfer::CODE => static::RESPONSE_CODE_SHARING_CART_FORBIDDEN,
                RestErrorMessageTransfer::STATUS => Response::HTTP_FORBIDDEN,
                RestErrorMessageTransfer::DETAIL => static::RESPONSE_DETAIL_SHARING_CART_FORBIDDEN,
            ],
            SharedSharedCartsRestApiConfig::ERROR_IDENTIFIER_FAILED_TO_SHARE_CART => [
                RestErrorMessageTransfer::CODE => static::RESPONSE_CODE_FAILED_TO_SHARE_CART,
                RestErrorMessageTransfer::STATUS => Response::HTTP_UNPROCESSABLE_ENTITY,
                RestErrorMessageTransfer::DETAIL => static::RESPONSE_DETAIL_FAILED_TO_SHARE_CART,
            ],
            SharedSharedCartsRestApiConfig::ERROR_IDENTIFIER_FAILED_TO_SAVE_SHARED_CART => [
                RestErrorMessageTransfer::CODE => static::RESPONSE_CODE_FAILED_TO_SAVE_SHARED_CART,
                RestErrorMessageTransfer::STATUS => Response::HTTP_UNPROCESSABLE_ENTITY,
                RestErrorMessageTransfer::DETAIL => static::RESPONSE_DETAIL_FAILED_TO_SAVE_SHARED_CART,
            ],
            SharedSharedCartsRestApiConfig::ERROR_IDENTIFIER_QUOTE_PERMISSION_GROUP_NOT_FOUND => [
                RestErrorMessageTransfer::CODE => static::RESPONSE_CODE_CART_PERMISSION_GROUP_NOT_FOUND,
                RestErrorMessageTransfer::STATUS => Response::HTTP_UNPROCESSABLE_ENTITY,
                RestErrorMessageTransfer::DETAIL => static::RESPONSE_DETAIL_CART_PERMISSION_GROUP_NOT_FOUND,
            ],
        ];
    }
}
