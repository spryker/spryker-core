<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthAuth0\Business\Expander;

use Generated\Shared\Transfer\AccessTokenRequestTransfer;
use Spryker\Zed\OauthAuth0\OauthAuth0Config;

class CacheKeySeedAccessTokenRequestExpander implements CacheKeySeedAccessTokenRequestExpanderInterface
{
    /**
     * @var \Spryker\Zed\OauthAuth0\OauthAuth0Config
     */
    protected OauthAuth0Config $auth0Config;

    /**
     * @param \Spryker\Zed\OauthAuth0\OauthAuth0Config $auth0Config
     */
    public function __construct(OauthAuth0Config $auth0Config)
    {
        $this->auth0Config = $auth0Config;
    }

    /**
     * @param \Generated\Shared\Transfer\AccessTokenRequestTransfer $accessTokenRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AccessTokenRequestTransfer
     */
    public function expand(AccessTokenRequestTransfer $accessTokenRequestTransfer): AccessTokenRequestTransfer
    {
        if ($accessTokenRequestTransfer->getProviderName() !== $this->auth0Config->getProviderName()) {
            return $accessTokenRequestTransfer;
        }

        return $accessTokenRequestTransfer
            ->setCacheKeySeed($this->getCredentialsHash());
    }

    /**
     * @return string
     */
    protected function getCredentialsHash(): string
    {
        return sha1($this->auth0Config->getClientId() . $this->auth0Config->getClientSecret());
    }
}
