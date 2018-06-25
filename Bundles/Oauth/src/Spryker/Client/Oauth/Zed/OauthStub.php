<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Oauth\Zed;

use Generated\Shared\Transfer\OauthAccessTokenValidationRequestTransfer;
use Generated\Shared\Transfer\OauthAccessTokenValidationResponseTransfer;
use Generated\Shared\Transfer\OauthRequestTransfer;
use Generated\Shared\Transfer\OauthResponseTransfer;
use Spryker\Client\Oauth\Dependency\Client\OauthToZedRequestClientInterface;

class OauthStub implements OauthStubInterface
{
    /**
     * @var \Spryker\Client\Oauth\Dependency\Client\OauthToZedRequestClientInterface
     */
    protected $zedRequestClient;

    /**
     * @param \Spryker\Client\Oauth\Dependency\Client\OauthToZedRequestClientInterface $zedRequestClient
     */
    public function __construct(OauthToZedRequestClientInterface $zedRequestClient)
    {
        $this->zedRequestClient = $zedRequestClient;
    }

    /**
     * @param \Generated\Shared\Transfer\OauthRequestTransfer $oauthRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OauthResponseTransfer
     */
    public function processAccessTokenRequest(OauthRequestTransfer $oauthRequestTransfer): OauthResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\OauthResponseTransfer $oauthResponseTransfer */
        $oauthResponseTransfer = $this->zedRequestClient->call('/oauth/gateway/process-access-token-request', $oauthRequestTransfer);

        return $oauthResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OauthAccessTokenValidationRequestTransfer $authAccessTokenValidationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OauthAccessTokenValidationResponseTransfer
     */
    public function validateAccessToken(OauthAccessTokenValidationRequestTransfer $authAccessTokenValidationRequestTransfer): OauthAccessTokenValidationResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\OauthAccessTokenValidationResponseTransfer $oauthAccessTokenValidationResponseTransfer */
        $oauthAccessTokenValidationResponseTransfer = $this->zedRequestClient->call('/oauth/gateway/validate-access-token', $authAccessTokenValidationRequestTransfer);

        return $oauthAccessTokenValidationResponseTransfer;
    }
}
