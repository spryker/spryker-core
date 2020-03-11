<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SalesReturnsRestApi;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\Kernel\AbstractBundleConfig;
use Spryker\Shared\SalesReturnsRestApi\SalesReturnsRestApiConfig as SalesReturnsRestApiSharedConfig;
use Symfony\Component\HttpFoundation\Response;

class SalesReturnsRestApiConfig extends AbstractBundleConfig
{
    public const RESOURCE_RETURNS = 'returns';
    public const RESOURCE_RETURNABLE_ITEMS = 'returnable-items';
    public const RESOURCE_RETURN_REASONS = 'return-reasons';

    // TODO: clarify it.
    public const RESPONSE_RETURN_CANT_BE_CREATED = '3302';
    public const RESPONSE_CANT_FIND_RETURN = '801';

    public const EXCEPTION_MESSAGE_RETURN_CANT_BE_CREATED = 'Return can\'t be created.';
    public const EXCEPTION_MESSAGE_CANT_FIND_RETURN = 'Can\'t find return by the given return reference';

    /**
     * @return (int|string)[][]
     */
    public function getErrorIdentifierToRestErrorMapping(): array
    {
        return [
            SalesReturnsRestApiSharedConfig::ERROR_IDENTIFIER_FAILED_CREATE_RETURN => [
                RestErrorMessageTransfer::CODE => static::RESPONSE_RETURN_CANT_BE_CREATED,
                RestErrorMessageTransfer::STATUS => Response::HTTP_BAD_REQUEST,
                RestErrorMessageTransfer::DETAIL => static::EXCEPTION_MESSAGE_RETURN_CANT_BE_CREATED,
            ],
            SalesReturnsRestApiSharedConfig::ERROR_IDENTIFIER_RETURN_NOT_FOUND => [
                RestErrorMessageTransfer::CODE => static::EXCEPTION_MESSAGE_CANT_FIND_RETURN,
                RestErrorMessageTransfer::STATUS => Response::HTTP_NOT_FOUND,
                RestErrorMessageTransfer::DETAIL => static::RESPONSE_CANT_FIND_RETURN,
            ],
        ];
    }
}
