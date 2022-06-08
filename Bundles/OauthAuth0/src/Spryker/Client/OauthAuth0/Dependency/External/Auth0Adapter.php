<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\OauthAuth0\Dependency\External;

use League\OAuth2\Client\Token\AccessTokenInterface;
use Riskio\OAuth2\Client\Provider\Auth0;

class Auth0Adapter implements Auth0AdapterInterface
{
    /**
     * @var \Riskio\OAuth2\Client\Provider\Auth0
     */
    protected $auth0Client;

    /**
     * @param \Riskio\OAuth2\Client\Provider\Auth0 $auth0Client
     */
    public function __construct(Auth0 $auth0Client)
    {
        $this->auth0Client = $auth0Client;
    }

    /**
     * @param string $grantType
     * @param array $options
     *
     * @return \League\OAuth2\Client\Token\AccessTokenInterface
     */
    public function getAccessToken(string $grantType, array $options = []): AccessTokenInterface
    {
        return $this->auth0Client->getAccessToken($grantType, $options);
    }
}
