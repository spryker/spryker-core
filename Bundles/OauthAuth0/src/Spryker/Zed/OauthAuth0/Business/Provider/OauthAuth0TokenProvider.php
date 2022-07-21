<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthAuth0\Business\Provider;

use Generated\Shared\Transfer\AccessTokenRequestTransfer;
use Generated\Shared\Transfer\AccessTokenResponseTransfer;
use Spryker\Client\OauthAuth0\OauthAuth0ClientInterface;

class OauthAuth0TokenProvider implements OauthAuth0TokenProviderInterface
{
    /**
     * @var \Spryker\Client\OauthAuth0\OauthAuth0ClientInterface
     */
    protected $oauthAuth0Client;

    /**
     * @param \Spryker\Client\OauthAuth0\OauthAuth0ClientInterface $oauthAuth0Client
     */
    public function __construct(OauthAuth0ClientInterface $oauthAuth0Client)
    {
        $this->oauthAuth0Client = $oauthAuth0Client;
    }

    /**
     * @param \Generated\Shared\Transfer\AccessTokenRequestTransfer $accessTokenRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AccessTokenResponseTransfer
     */
    public function getAccessToken(AccessTokenRequestTransfer $accessTokenRequestTransfer): AccessTokenResponseTransfer
    {
        return $this->oauthAuth0Client->getAccessToken($accessTokenRequestTransfer);
    }
}
