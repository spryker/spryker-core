<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthClient\Business\Expander;

use Generated\Shared\Transfer\AccessTokenRequestOptionsTransfer;
use Generated\Shared\Transfer\AccessTokenRequestTransfer;
use Spryker\Zed\OauthClient\OauthClientConfig;

class AccessTokenRequestExpander implements AccessTokenRequestExpanderInterface
{
    /**
     * @var \Spryker\Zed\OauthClient\OauthClientConfig
     */
    protected OauthClientConfig $oauthClientConfig;

    /**
     * @param \Spryker\Zed\OauthClient\OauthClientConfig $oauthClientConfig
     */
    public function __construct(OauthClientConfig $oauthClientConfig)
    {
        $this->oauthClientConfig = $oauthClientConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\AccessTokenRequestTransfer $accessTokenRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AccessTokenRequestTransfer
     */
    public function expandAccessTokenRequestWithTenantIdentifier(AccessTokenRequestTransfer $accessTokenRequestTransfer): AccessTokenRequestTransfer
    {
        $tenantIdentifier = $this->oauthClientConfig->getTenantIdentifier();
        if (!$tenantIdentifier) {
            return $accessTokenRequestTransfer;
        }

        $accessTokenRequestOptionsTransfer = $accessTokenRequestTransfer->getAccessTokenRequestOptions();
        if ($accessTokenRequestOptionsTransfer === null) {
            $accessTokenRequestOptionsTransfer = new AccessTokenRequestOptionsTransfer();
        }

        $accessTokenRequestOptionsTransfer->setTenantIdentifier($tenantIdentifier);
        $accessTokenRequestTransfer->setAccessTokenRequestOptions($accessTokenRequestOptionsTransfer);

        return $accessTokenRequestTransfer;
    }
}
