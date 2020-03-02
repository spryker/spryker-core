<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SalesReturnsRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;
use Spryker\Shared\SalesReturnsRestApi\SalesReturnsRestApiConfig as SalesReturnsRestApiSharedConfig;
use Symfony\Component\HttpFoundation\Response;

class SalesReturnsRestApiConfig extends AbstractBundleConfig
{
    public const RESOURCE_RETURNS = 'returns';
    public const RESOURCE_RETURN_REASONS = 'return-reasons';

    /**
     * @return array
     */
    public function getErrorIdentifierToRestErrorMapping(): array
    {
        return [
            SalesReturnsRestApiSharedConfig::ERROR_IDENTIFIER_FAILED_CREATE_RETURN => [
                RestErrorMessageTransfer::CODE => static::RESPONSE_CODE_CART_NOT_FOUND,
                RestErrorMessageTransfer::STATUS => Response::HTTP_BAD_REQUEST,
                RestErrorMessageTransfer::DETAIL => static::EXCEPTION_MESSAGE_CART_WITH_ID_NOT_FOUND,
            ],
            SalesReturnsRestApiSharedConfig::ERROR_IDENTIFIER_CART_CODE_CANT_BE_ADDED => [
                RestErrorMessageTransfer::CODE => static::RESPONSE_CODE_CART_CODE_CANT_BE_ADDED,
                RestErrorMessageTransfer::STATUS => Response::HTTP_UNPROCESSABLE_ENTITY,
                RestErrorMessageTransfer::DETAIL => static::EXCEPTION_MESSAGE_CART_CODE_CANT_BE_ADDED,
            ],
            SalesReturnsRestApiSharedConfig::ERROR_IDENTIFIER_CART_CODE_NOT_FOUND => [
                RestErrorMessageTransfer::CODE => static::RESPONSE_CODE_CART_CODE_NOT_FOUND,
                RestErrorMessageTransfer::STATUS => Response::HTTP_UNPROCESSABLE_ENTITY,
                RestErrorMessageTransfer::DETAIL => static::EXCEPTION_MESSAGE_CART_CODE_NOT_FOUND,
            ],
        ];
    }
}
