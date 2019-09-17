<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\AuthRestApi\Zed;

use Generated\Shared\Transfer\OauthRequestTransfer;
use Generated\Shared\Transfer\OauthResponseTransfer;
use Spryker\Client\AuthRestApi\Dependency\Client\AuthRestApiToZedRequestClientInterface;

class AuthRestApiZedStub implements AuthRestApiZedStubInterface
{
    /**
     * @var \Spryker\Client\AuthRestApi\Dependency\Client\AuthRestApiToZedRequestClientInterface
     */
    protected $zedRequestClient;

    /**
     * @param \Spryker\Client\AuthRestApi\Dependency\Client\AuthRestApiToZedRequestClientInterface $zedRequestClient
     */
    public function __construct(AuthRestApiToZedRequestClientInterface $zedRequestClient)
    {
        $this->zedRequestClient = $zedRequestClient;
    }

    /**
     * @param \Generated\Shared\Transfer\OauthRequestTransfer $oauthRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OauthResponseTransfer
     */
    public function processAccessToken(OauthRequestTransfer $oauthRequestTransfer): OauthResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\OauthResponseTransfer $oauthResponseTransfer */
        $oauthResponseTransfer = $this->zedRequestClient->call('/auth-rest-api/gateway/process-access-token', $oauthRequestTransfer);

        return $oauthResponseTransfer;
    }
}
