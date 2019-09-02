<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WishlistsRestApi;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\Kernel\AbstractBundleConfig;
use Spryker\Shared\WishlistsRestApi\WishlistsRestApiConfig as SharedWishlistRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class WishlistsRestApiConfig extends AbstractBundleConfig
{
    public const RESOURCE_WISHLISTS = 'wishlists';
    public const RESOURCE_WISHLIST_ITEMS = 'wishlist-items';

    public const RESPONSE_CODE_WISHLIST_UNKNOWN_ERROR = '200';
    public const RESPONSE_CODE_WISHLIST_NOT_FOUND = '201';
    public const RESPONSE_CODE_WISHLIST_WITH_SAME_NAME_ALREADY_EXISTS = '202';
    public const RESPONSE_CODE_WISHLIST_CANT_CREATE_WISHLIST = '203';
    public const RESPONSE_CODE_WISHLIST_CANT_UPDATE_WISHLIST = '204';
    public const RESPONSE_CODE_WISHLIST_CANT_DELETE_WISHLIST = '205';
    public const RESPONSE_CODE_WISHLIST_CANT_ADD_ITEM = '206';
    public const RESPONSE_CODE_NO_ITEM_WITH_PROVIDED_ID = '208';
    public const RESPONSE_CODE_ID_IS_NOT_SPECIFIED = '209';
    public const RESPONSE_CODE_WISHLIST_NAME_INVALID = '210';

    public const RESPONSE_DETAIL_WISHLIST_UNKNOWN_ERROR = 'Unknown error.';
    public const RESPONSE_DETAIL_WISHLIST_NOT_FOUND = 'Can\'t find wishlist.';
    public const RESPONSE_DETAIL_WISHLIST_WITH_SAME_NAME_ALREADY_EXISTS = 'A wishlist with the same name already exists.';
    public const RESPONSE_DETAIL_WISHLIST_CANT_ADD_ITEM = 'Can\'t add an item.';
    public const RESPONSE_DETAIL_NO_ITEM_WITH_PROVIDED_SKU = 'No item with provided sku in wishlist.';
    public const RESPONSE_DETAIL_ID_IS_NOT_SPECIFIED = 'Id is not specified.';
    public const RESPONSE_DETAIL_WISHLIST_NAME_INVALID = 'Please enter name using only letters, numbers, underscores, spaces or dashes.';
    public const RESPONSE_DETAIL_WISHLIST_CANT_BE_UPDATED = 'Can\'t update wishlist';
    public const RESPONSE_DETAIL_WISHLIST_CANT_BE_CREATED = 'Can\'t create wishlist';

    /**
     * @return array
     */
    public function getErrorIdentifierToRestErrorMapping(): array
    {
        return [
            SharedWishlistRestApiConfig::ERROR_IDENTIFIER_WISHLIST_NOT_FOUND => [
                RestErrorMessageTransfer::CODE => static::RESPONSE_CODE_WISHLIST_NOT_FOUND,
                RestErrorMessageTransfer::STATUS => Response::HTTP_NOT_FOUND,
                RestErrorMessageTransfer::DETAIL => static::RESPONSE_DETAIL_WISHLIST_NOT_FOUND,
            ],
            SharedWishlistRestApiConfig::ERROR_IDENTIFIER_WISHLIST_NAME_ALREADY_EXIST => [
                RestErrorMessageTransfer::CODE => static::RESPONSE_CODE_WISHLIST_WITH_SAME_NAME_ALREADY_EXISTS,
                RestErrorMessageTransfer::STATUS => Response::HTTP_UNPROCESSABLE_ENTITY,
                RestErrorMessageTransfer::DETAIL => static::RESPONSE_DETAIL_WISHLIST_WITH_SAME_NAME_ALREADY_EXISTS,
            ],
            SharedWishlistRestApiConfig::ERROR_IDENTIFIER_WISHLIST_NAME_WRONG_FORMAT => [
                RestErrorMessageTransfer::CODE => static::RESPONSE_CODE_WISHLIST_NAME_INVALID,
                RestErrorMessageTransfer::STATUS => Response::HTTP_UNPROCESSABLE_ENTITY,
                RestErrorMessageTransfer::DETAIL => static::RESPONSE_DETAIL_WISHLIST_NAME_INVALID,
            ],
            SharedWishlistRestApiConfig::ERROR_IDENTIFIER_WISHLIST_CANT_BE_UPDATED => [
                RestErrorMessageTransfer::CODE => static::RESPONSE_CODE_WISHLIST_CANT_UPDATE_WISHLIST,
                RestErrorMessageTransfer::STATUS => Response::HTTP_UNPROCESSABLE_ENTITY,
                RestErrorMessageTransfer::DETAIL => static::RESPONSE_DETAIL_WISHLIST_CANT_BE_UPDATED,
            ],
        ];
    }
}
