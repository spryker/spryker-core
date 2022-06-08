<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\OauthApi\Dependency\Client;

use Generated\Shared\Transfer\GlueAuthenticationRequestTransfer;
use Generated\Shared\Transfer\GlueAuthenticationResponseTransfer;

class OauthApiToAuthenticationClientBridge implements OauthApiToAuthenticationClientInterface
{
    /**
     * @var \Spryker\Client\Authentication\AuthenticationClientInterface
     */
    protected $authenticationClient;

    /**
     * @param \Spryker\Client\Authentication\AuthenticationClientInterface $authenticationClient
     */
    public function __construct($authenticationClient)
    {
        $this->authenticationClient = $authenticationClient;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueAuthenticationRequestTransfer $glueAuthenticationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueAuthenticationResponseTransfer
     */
    public function authenticate(GlueAuthenticationRequestTransfer $glueAuthenticationRequestTransfer): GlueAuthenticationResponseTransfer
    {
        return $this->authenticationClient->authenticate($glueAuthenticationRequestTransfer);
    }
}
