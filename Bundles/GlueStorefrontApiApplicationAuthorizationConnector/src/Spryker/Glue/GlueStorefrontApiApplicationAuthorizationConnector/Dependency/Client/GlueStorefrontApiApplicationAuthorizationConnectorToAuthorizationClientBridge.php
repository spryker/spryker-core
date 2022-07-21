<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueStorefrontApiApplicationAuthorizationConnector\Dependency\Client;

use Generated\Shared\Transfer\AuthorizationRequestTransfer;
use Generated\Shared\Transfer\AuthorizationResponseTransfer;

class GlueStorefrontApiApplicationAuthorizationConnectorToAuthorizationClientBridge implements GlueStorefrontApiApplicationAuthorizationConnectorToAuthorizationClientInterface
{
    /**
     * @var \Spryker\Client\Authorization\AuthorizationClientInterface
     */
    protected $authorizationClient;

    /**
     * @param \Spryker\Client\Authorization\AuthorizationClientInterface $authorizationClient
     */
    public function __construct($authorizationClient)
    {
        $this->authorizationClient = $authorizationClient;
    }

    /**
     * @param \Generated\Shared\Transfer\AuthorizationRequestTransfer $authorizationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AuthorizationResponseTransfer
     */
    public function authorize(AuthorizationRequestTransfer $authorizationRequestTransfer): AuthorizationResponseTransfer
    {
        return $this->authorizationClient->authorize($authorizationRequestTransfer);
    }
}
