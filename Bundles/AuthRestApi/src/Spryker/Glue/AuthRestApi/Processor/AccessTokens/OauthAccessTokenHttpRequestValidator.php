<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AuthRestApi\Processor\AccessTokens;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Symfony\Component\HttpFoundation\Request;

class OauthAccessTokenHttpRequestValidator implements OauthAccessTokenHttpRequestValidatorInterface
{
    /**
     * @var \Spryker\Glue\AuthRestApi\Processor\AccessTokens\OauthAccessTokenValidatorInterface
     */
    protected $oauthAccessTokenValidator;

    /**
     * @param \Spryker\Glue\AuthRestApi\Processor\AccessTokens\OauthAccessTokenValidatorInterface $oauthAccessTokenValidator
     */
    public function __construct(OauthAccessTokenValidatorInterface $oauthAccessTokenValidator)
    {
        $this->oauthAccessTokenValidator = $oauthAccessTokenValidator;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer|null
     */
    public function validate(Request $request): ?RestErrorMessageTransfer
    {
        $restErrorCollectionTransfer = $this->oauthAccessTokenValidator->validate($request);

        if ($restErrorCollectionTransfer !== null && $restErrorCollectionTransfer->getRestErrors()->count()) {
            return $restErrorCollectionTransfer->getRestErrors()[0];
        }

        return null;
    }
}
