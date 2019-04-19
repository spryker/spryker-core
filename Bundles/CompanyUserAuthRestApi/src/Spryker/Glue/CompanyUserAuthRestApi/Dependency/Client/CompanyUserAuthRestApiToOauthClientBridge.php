<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyUserAuthRestApi\Dependency\Client;

use Generated\Shared\Transfer\OauthRequestTransfer;
use Generated\Shared\Transfer\OauthResponseTransfer;

class CompanyUserAuthRestApiToOauthClientBridge implements CompanyUserAuthRestApiToOauthClientInterface
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
     * @param \Generated\Shared\Transfer\OauthRequestTransfer $oauthRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OauthResponseTransfer
     */
    public function processAccessTokenRequest(OauthRequestTransfer $oauthRequestTransfer): OauthResponseTransfer
    {
        return $this->oauthClient->processAccessTokenRequest($oauthRequestTransfer);
    }
}
