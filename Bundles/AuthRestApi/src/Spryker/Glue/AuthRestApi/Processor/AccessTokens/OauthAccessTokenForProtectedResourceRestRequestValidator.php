<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AuthRestApi\Processor\AccessTokens;

use Generated\Shared\Transfer\RestErrorCollectionTransfer;
use Spryker\Glue\AuthRestApi\AuthRestApiConfig;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class OauthAccessTokenForProtectedResourceRestRequestValidator extends BaseOauthAccessTokenRestRequestValidator
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\RestErrorCollectionTransfer|null
     */
    public function validate(Request $request): ?RestErrorCollectionTransfer
    {
        $isProtected = $request->attributes->get(static::REQUEST_ATTRIBUTE_IS_PROTECTED, false);

        $authorizationToken = $request->headers->get(AuthRestApiConfig::HEADER_AUTHORIZATION);

        if (!$isProtected || !$authorizationToken) {
            return null;
        }

        if (!$this->validateAccessToken($authorizationToken)) {
            return (new RestErrorCollectionTransfer())->addRestError(
                $this->createErrorMessageTransfer(
                    AuthRestApiConfig::RESPONSE_DETAIL_INVALID_ACCESS_TOKEN,
                    Response::HTTP_UNAUTHORIZED,
                    AuthRestApiConfig::RESPONSE_CODE_ACCESS_CODE_INVALID
                )
            );
        }

        return null;
    }
}
