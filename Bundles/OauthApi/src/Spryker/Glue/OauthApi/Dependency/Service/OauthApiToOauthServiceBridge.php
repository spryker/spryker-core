<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\OauthApi\Dependency\Service;

use Generated\Shared\Transfer\OauthAccessTokenDataTransfer;

class OauthApiToOauthServiceBridge implements OauthApiToOauthServiceInterface
{
    /**
     * @var \Spryker\Service\Oauth\OauthServiceInterface
     */
    protected $oauthService;

    /**
     * @param \Spryker\Service\Oauth\OauthServiceInterface $oauthService
     */
    public function __construct($oauthService)
    {
        $this->oauthService = $oauthService;
    }

    /**
     * @param string $accessToken
     *
     * @return \Generated\Shared\Transfer\OauthAccessTokenDataTransfer
     */
    public function extractAccessTokenData(string $accessToken): OauthAccessTokenDataTransfer
    {
        return $this->oauthService->extractAccessTokenData($accessToken);
    }
}
