<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\OauthApi\Dependency\Client;

use Generated\Shared\Transfer\OauthAccessTokenValidationRequestTransfer;
use Generated\Shared\Transfer\OauthAccessTokenValidationResponseTransfer;

class OauthApiToOauthClientBridge implements OauthApiToOauthClientInterface
{
    /**
     * @var \Spryker\Client\Oauth\OauthClientInterface
     */
    protected $oauthClient;

    /**
     * @param \Spryker\Client\Oauth\OauthClientInterface $oauthClient
     */
    public function __construct($oauthClient)
    {
        $this->oauthClient = $oauthClient;
    }

    /**
     * @param \Generated\Shared\Transfer\OauthAccessTokenValidationRequestTransfer $oauthAccessTokenValidationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OauthAccessTokenValidationResponseTransfer
     */
    public function validateOauthAccessToken(
        OauthAccessTokenValidationRequestTransfer $oauthAccessTokenValidationRequestTransfer
    ): OauthAccessTokenValidationResponseTransfer {
        return $this->oauthClient->validateOauthAccessToken($oauthAccessTokenValidationRequestTransfer);
    }
}
